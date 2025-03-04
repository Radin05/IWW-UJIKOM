<?php
namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\KasRt;
use App\Models\KasRw;
use App\Models\KegiatanRw;
use App\Models\Keluarga;
use App\Models\RT;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now('Asia/Jakarta')->year);

        $kasRw       = KasRw::first();
        $jumlahrt    = RT::count();
        $jumlahwarga = Keluarga::count();

        $kegiatanRw = KegiatanRw::orderBy('tanggal_kegiatan', 'desc')->get();

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil RT ID berdasarkan no_kk_keluarga dari user
        $rt_id = Keluarga::where('no_kk', $user->no_kk_keluarga)->value('rt_id');

        // Ambil data kasRT berdasarkan rt_id
        $kasRT = KasRt::where('rt_id', $rt_id)->first();

        $superadmins = User::where('role', 'superadmin')->get();

        return view('user.index', compact('year', 'jumlahrt', 'jumlahwarga', 'kegiatanRw', 'kasRw', 'kasRT', 'superadmins'));
    }
}
