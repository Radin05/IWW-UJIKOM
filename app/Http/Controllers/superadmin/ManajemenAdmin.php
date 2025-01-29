<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RT;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenAdmin extends Controller
{
    public function index()
    {
        $admin = User::where('role', 'admin')->with(['activityLog.user', 'rt'])
            ->orderBy(RT::select('nama_RT')->whereColumn('id', 'rt_id'), 'asc')
            ->get();

        $rts = RT::orderBy('nama_RT', 'asc')->get();

        return view('superadmin.admin.index', compact('admin', 'rts'));
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

        return redirect()->route('superadmin.manajemen-admin.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'rt_id' => "required|string|max:3",
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'rt_id' => $request->rt_id,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengubah field admin RT dari email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $user->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.manajemen-admin.index')->with('success', 'Admin berhasil diperbarui.');
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

        return redirect()->route('superadmin.manajemen-admin.index')->with('success', 'Akun Admin RT berhasil dihapus');
    }
}
