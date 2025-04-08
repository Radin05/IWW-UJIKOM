<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HistoriKasRT;
use App\Models\KasRt;
use App\Models\KegiatanRt;
use App\Models\Keluarga;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $carbon         = Carbon::now('Asia/Jakarta');
        $kegiatan       = KegiatanRt::whereBetween('tanggal_kegiatan', [$carbon, $carbon->copy()->addDays(7)])->get();
        $jumlahKeluarga = Keluarga::where('rt_id', $user->rt_id)->count();
        $keluargas      = Keluarga::where('rt_id', $user->rt_id)->pluck('no_kk');
        $pembayaran     = Pembayaran::where('year', $year)
            ->whereIn('no_kk_keluarga', $keluargas)
            ->sum('sejumlah');
        $kasRt       = KasRt::where('rt_id', $user->rt_id)->first();
        $pengeluaran = PengeluaranKasRt::where('rt_id', $user->rt_id)->sum('nominal');

        // Hitung histori kas RT dalam 5 bulan terakhir termasuk bulan ini
        $currentDate = Carbon::now();
        $startDate   = $currentDate->copy()->subMonths(4)->startOfMonth();
        $endDate     = $currentDate->copy()->endOfMonth();

        $historiKasBulanan = HistoriKasRT::where('rt_id', $user->rt_id)
            ->whereBetween('tgl_pembaruan_kas', [$startDate, $endDate])
            ->select(
                DB::raw('MONTH(tgl_pembaruan_kas) as month'),
                DB::raw('MAX(tgl_pembaruan_kas) as last_update')
            )
            ->groupBy('month')
            ->get();

        // Ambil nominal dari histori kas berdasarkan tanggal terakhir di setiap bulan
        $dataKasRT = [];
        foreach ($historiKasBulanan as $histori) {
            $nominal = HistoriKasRT::where('rt_id', $user->rt_id)
                ->whereDate('tgl_pembaruan_kas', $histori->last_update)
                ->orderByDesc('tgl_pembaruan_kas')
                ->value('nominal');

            $dataKasRT[$histori->month] = $nominal ?? 0;
        }

        // Hitung pengeluaran RT dalam 5 bulan terakhir termasuk bulan ini
        $pengeluaranBulanan = PengeluaranKasRt::where('rt_id', $user->rt_id)
            ->whereBetween(DB::raw('DATE(tgl_pengeluaran)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('MONTH(tgl_pengeluaran) as month, SUM(nominal) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Buat array label dan data
        $labels          = [];
        $dataPengeluaran = [];
        $chartKasRT      = [];

        for ($i = 4; $i >= 0; $i--) {
            $monthNumber = $currentDate->copy()->subMonths($i)->format('n'); // Nomor bulan (1-12)
            $labels[]    = $currentDate->copy()->subMonths($i)->format('M'); // Format nama bulan (Jan, Feb, dst.)

            $chartKasRT[]      = isset($dataKasRT[$monthNumber]) ? (int) $dataKasRT[$monthNumber] : 0;
            $dataPengeluaran[] = isset($pengeluaranBulanan[$monthNumber]) ? (int) $pengeluaranBulanan[$monthNumber] : 0;
        }

        // Kirim ke view
        return view('admin.dashboard', compact(
            'nama_RT', 'carbon', 'year', 'jumlahKeluarga', 'pembayaran', 'kasRt', 'pengeluaran', 'kegiatan',
            'labels', 'chartKasRT', 'dataPengeluaran'
        ));

    }

}
