<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index($nama_RT)
    {
        $user = Auth::user();

        if (!$nama_RT) {
            abort(404, 'Parameter nama_RT tidak ditemukan.');
        }

        if ($user->rt->nama_RT !== $nama_RT) {
            abort(403, 'Anda tidak memiliki akses ke RT ini.');
        }

        $usersInRT = User::where('rt_id', $user->rt_id)->get();

        return view('admin.dashboard', compact('usersInRT', 'nama_RT'));
    }
}
