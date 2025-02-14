<?php
namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RT;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ManajemenAdmin extends Controller
{
    public function index()
    {
        $admin = User::where('role', 'admin')->with(['activityLog.user', 'rt'])
            ->orderBy(RT::select('nama_RT')->whereColumn('id', 'rt_id'), 'asc')
            ->get();

        $rts = RT::orderBy('nama_RT', 'asc')->get();

        confirmDelete('Hapus Akun Admin', 'Yakin ingin menghapus RT ini?');

        return view('operator.admin.index', compact('admin', 'rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rt_id'    => 'required|exists:rts,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'rt_id'    => $request->rt_id,
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
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'rt_id' => "required|string|max:3",
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

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'rt_id' => $request->rt_id,
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

    public function destroy($id)
    {
        $user = User::findOrFail($id);

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
