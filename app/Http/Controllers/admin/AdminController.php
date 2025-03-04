<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KasRt;
use App\Models\KegiatanRt;
use App\Models\Keluarga;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request, $nama_RT)
    {
        $user = Auth::user();

        if (! $nama_RT) {
            abort(404, 'Parameter nama_RT tidak ditemukan.');
        }

        if ($user->rt->nama_RT !== $nama_RT) {
            abort(403, 'Anda tidak memiliki akses ke RT ini.');
        }

        $year = $request->input('year', Carbon::now('Asia/Jakarta')->year);

        if (! is_numeric($year) || $year < 1900 || $year > Carbon::now('Asia/Jakarta')->year + 3) {
            $year = Carbon::now('Asia/Jakarta')->year; // Set ke tahun saat ini jika tahun tidak valid
        }

        $carbon      = Carbon::now('Asia/Jakarta');
        $kegiatan    = KegiatanRt::whereBetween('tanggal_kegiatan', [$carbon, $carbon->copy()->addDays(7)])->get();
        $jumlahKeluarga    = Keluarga::where('rt_id', $user->rt_id)->count();
        $keluargas    = Keluarga::where('rt_id', $user->rt_id)->pluck('no_kk');
        $pembayaran  = Pembayaran::where('year', $year)
            ->whereIn('no_kk_keluarga', $keluargas)
            ->sum('sejumlah');
        $kasRt       = KasRt::where('rt_id', $user->rt_id)->first();
        $pengeluaran = PengeluaranKasRt::where('rt_id', $user->rt_id)->sum('nominal');

        return view('admin.dashboard', compact( 'nama_RT', 'carbon', 'year', 'jumlahKeluarga', 'pembayaran', 'kasRt', 'pengeluaran', 'kegiatan'));
    }
}
