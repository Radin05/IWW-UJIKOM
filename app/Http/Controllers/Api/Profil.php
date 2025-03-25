<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Keluarga as ModalKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Profil extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil data keluarga berdasarkan no_kk
        $keluarga = ModalKeluarga::where('no_kk', $user->no_kk_keluarga)->first();
        
        return response()->json([
            'user'     => $user,
            'keluarga' => $keluarga,
        ]);
    }

}
