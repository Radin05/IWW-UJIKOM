<?php
namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ManajemenSuperAdmin extends Controller
{
    public function index()
    {
        $superadmin     = User::where('role', 'superadmin')->with(['activityLog.user'])->get();
        $digunakan      = User::pluck('kedudukan')->toArray();
        $opsi_kedudukan = ['Ketua RW', 'Wakil Ketua RW', 'Sekretaris RW', 'Bendahara RW', 'Humas RW', 'Keamanan RW'];

        confirmDelete('Hapus akun?', 'Akun akan terhapus dan tidak dapat dipakai lagi');

        return view('operator.index', compact('superadmin', 'digunakan', 'opsi_kedudukan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
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
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'superadmin',
            'foto'      => $fotoPath,
            'kedudukan' => $request->kedudukan,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat akun superadmin dengan email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $user->id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Admin RW berhasil dibuat.');

        return redirect()->route('operator.manajemen-superadmin.index')
            ->with('success', 'Admin RW berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'superadmin')->findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
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
            'name'      => $request->name,
            'email'     => $request->email,
            'kedudukan' => $request->kedudukan,
        ]);

        // $user->password = Hash::make($request->password);
        $user->save();

        // Simpan log aktivitas jika ada perubahan
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

        Alert::success('Success', 'data berhasil diperbarui.');

        return redirect()->route('operator.manajemen-superadmin.index')->with('success', 'Superadmin berhasil diperbarui.');
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::where('role', 'superadmin')->findOrFail($id);

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
        $user = User::where('role', 'superadmin')->findOrFail($id);

        // Hapus foto jika ada
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => "Menghapus Superadmin dengan nama Email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Superadmin berhasil dihapus.');

        return redirect()->route('operator.manajemen-superadmin.index')->with('success', 'Email berhasil dihapus');
    }
}
