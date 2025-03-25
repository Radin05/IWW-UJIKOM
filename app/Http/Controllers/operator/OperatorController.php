<?php

namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\RT;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        $admin = User::where('role', 'admin')->count();
        $superadmin = User::where('role', 'superadmin')->count();
        $operator = User::where('role', 'operator')->count();
        $operatorData = User::where('role', 'operator')->get(); ;
        $rts = RT::count();
        $carbon = Carbon::now('Asia/Jakarta');

        return view('operator.dashboard', compact('admin', 'superadmin', 'operator', 'operatorData', 'rts', 'carbon'));
    }
}
