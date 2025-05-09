<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'operator') {
            return redirect('/operator/dashboard');
        }
        elseif ($user->role === 'admin') {
            $nama_RT = $user->rt->nama_RT;

            return redirect('admin/'.$nama_RT.'/dashboard');
        }
        elseif ($user->role === 'superadmin') {
            return redirect('superadmin/dashboard');
        }
        else {
            return redirect('/user/dashboard');
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
