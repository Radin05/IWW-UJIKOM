<?php
namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RT;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ManajemenAdmin extends Controller
{
    public function index()
    {
        $admin = User::where('role', 'admin')->with(['activityLog.user', 'rt'])
            ->orderBy(RT::select('nama_RT')->whereColumn('id', 'rt_id'), 'asc')
            ->get();

        $rts = RT::orderBy('nama_RT', 'asc')->get();

        // $dipakai      = User::pluck('kedudukan')->toArray();
        $opsi_kedudukan = ['Ketua RT', 'Wakil Ketua RT', 'Sekretaris', 'Bendahara', 'Humas', 'Keamanan'];

        confirmDelete('Hapus Akun Admin', 'Yakin ingin menghapus RT ini?');

        return view('operator.admin.index', compact('admin', 'rts', 'opsi_kedudukan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'rt_id'     => 'required|exists:rts,id',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:4000',
            'kedudukan' => 'nullable|string|max:255',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoFile = $request->file('foto');
            $fileName = time() . '_' . $fotoFile->getClientOriginalName();
            $fotoPath = $fotoFile->storeAs('uploads/foto-users', $fileName, 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'rt_id'    => $request->rt_id,
            'foto'      => $fotoPath,
            'kedudukan' => $request->kedudukan,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat admin dengan email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $user->id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Data Admin Berhasil Dibuat.');
        return redirect()->route('operator.manajemen-admin.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'rt_id' => "required|string|max:3",
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:4000',
            'kedudukan' => 'nullable|string|max:255',
        ]);

        $oldData = $user->replicate();
        $changes = [];

        if ($oldData->name !== $request->name) {
            $changes[] = "Nama dari {$oldData->name} menjadi {$request->name}";
        }
        if ($oldData->email !== $request->email) {
            $changes[] = "Email dari {$oldData->email} menjadi {$request->email}";
        }
        if ($oldData->rt_id != $request->rt_id) {
            $changes[] = "RT dari {$oldData->rt_id} menjadi {$request->rt_id}";
        }
        if ($user->kedudukan !== $request->kedudukan) {
            $changes[] = "Kedudukan dari {$user->kedudukan} menjadi {$request->kedudukan}";
        }

        // Jika ada foto baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $fotoPath   = $request->file('foto')->store('uploads/foto-users', 'public');
            $user->foto = $fotoPath;
            $changes[]  = "Foto diperbarui";
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'rt_id' => $request->rt_id,
            'kedudukan' => $request->kedudukan,
        ]);

        if (! empty($changes)) {
            ActivityLog::create([
                'user_id'      => auth()->id(),
                'activity'     => 'update',
                'description'  => implode('<br>', $changes),
                'target_table' => 'users',
                'target_id'    => $user->id,
                'performed_at' => now(),
            ]);
        }

        Alert::success('Success', 'Akun Admin ' . $user->id . ' Berhasil Diubah.');
        return redirect()->route('operator.manajemen-admin.index');
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'updatepw',
            'description'  => "Password diperbarui untuk {$user->name}",
            'target_table' => 'users',
            'target_id'    => $user->id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Password berhasil diperbarui.');

        return redirect()->route('operator.manajemen-superadmin.index')->with('success', 'Password berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);

        $user->delete();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => "Menghapus data Admin RT dengan nama Email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Data Admin ' . $user->id . ' berhasil dibuat.');
        return redirect()->route('operator.manajemen-admin.index');
    }
}
