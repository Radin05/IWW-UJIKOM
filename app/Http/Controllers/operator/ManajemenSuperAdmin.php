<?php
namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenSuperAdmin extends Controller
{
    public function index()
    {
        $superadmin = User::where('role', 'superadmin')->with(['activityLog.user'])->get();
        return view('operator.index', compact('superadmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'superadmin',
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat akun superadmin dengan email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $user->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('operator.manajemen-superadmin.index')
            ->with('success', 'Admin RW berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $oldData = $user->replicate();
        $changes = [];

        if ($oldData->name !== $request->name) {
            $changes[] = "Nama dari {$oldData->name} menjadi {$request->name}";
        }
        if ($oldData->email !== $request->email) {
            $changes[] = "Email dari {$oldData->email} menjadi {$request->email}";
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $user->password = Hash::make($request->password);
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

        return redirect()->route('operator.manajemen-superadmin.index')->with('success', 'Superadmin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => "Menghapus Superadmin dengan nama Email {$user->email}",
            'target_table' => 'users',
            'target_id'    => $id,
            'performed_at' => now(),
        ]);

        return redirect()->route('operator.manajemen-superadmin.index')->with('success', 'Email berhasil dihapus');
    }
}
