<?php

namespace App\Http\Controllers\superadmin;

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
        return view('superadmin.index', compact('superadmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'superadmin',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'create',
            'description' => "Membuat akun superadmin dengan email {$user->email}",
            'target_table' => 'users',
            'target_id' => $user->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.manajemen-superadmin.index')
        ->with('success', 'SuperAdmin berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'update',
            'description' => "Mengedit field superadmin dengan email {$user->email}",
            'target_table' => 'users',
            'target_id' => $user->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.manajemen-superadmin.index')->with('success', 'Superadmin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'delete',
            'description' => "Menghapus Superadmin dengan Email {$user->email}",
            'target_table' => 'users',
            'target_id' => $id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.manajemen-superadmin.index')->with('success', 'Email berhasil dihapus');
    }
}
