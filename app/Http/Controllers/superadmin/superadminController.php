<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\HistoriKasRW;
use App\Models\KasRw;
use App\Models\KegiatanRw;
use App\Models\PengeluaranKasRw;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class superadminController extends Controller
{
    public function index()
    {
        $kasRw       = KasRw::first();
        $pengeluaran = PengeluaranKasRw::sum('nominal');
        $kegiatan    = KegiatanRw::orderBy('tanggal_kegiatan', 'desc')->get();
        $carbon      = Carbon::now('Asia/Jakarta');

        // Mulai dari 4 bulan sebelum bulan ini hingga bulan sekarang
        $currentDate = Carbon::now();
        $startDate   = $currentDate->copy()->subMonths(4)->startOfMonth();
        $endDate     = $currentDate->copy()->endOfMonth(); // Sampai akhir bulan saat ini

        $historiKasBulanan = HistoriKasRW::whereBetween('tgl_pembaruan_kas', [$startDate, $endDate])
            ->select(
                DB::raw('MONTH(tgl_pembaruan_kas) as month'),
                DB::raw('MAX(tgl_pembaruan_kas) as last_update')
            )
            ->groupBy('month')
            ->get();

        // Ambil nominal dari histori kas berdasarkan tanggal terakhir di setiap bulan
        $dataKasRW = [];
        foreach ($historiKasBulanan as $histori) {
            $nominal = HistoriKasRW::whereDate('tgl_pembaruan_kas', $histori->last_update)
                ->orderByDesc('tgl_pembaruan_kas')
                ->value('nominal');

            $dataKasRW[$histori->month] = $nominal ?? 0;
        }

        // Ambil pengeluaran dalam 5 bulan terakhir
        $pengeluaranBulanan = PengeluaranKasRw::whereBetween(DB::raw('DATE(tgl_pengeluaran)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('MONTH(tgl_pengeluaran) as month, SUM(nominal) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Buat label dan data untuk chart
        $labels          = [];
        $dataPengeluaran = [];
        $chartKasRW      = [];

        for ($i = 4; $i >= 0; $i--) {
            $monthNumber = $currentDate->copy()->subMonths($i)->format('n'); // Nomor bulan (1-12)
            $labels[]    = $currentDate->copy()->subMonths($i)->format('M'); // Nama bulan (Jan, Feb, dst.)

            $chartKasRW[]      = isset($dataKasRW[$monthNumber]) ? (int) $dataKasRW[$monthNumber] : 0;
            $dataPengeluaran[] = isset($pengeluaranBulanan[$monthNumber]) ? (int) $pengeluaranBulanan[$monthNumber] : 0;
        }

        return view('superadmin.dashboard', compact('kasRw', 'pengeluaran', 'kegiatan', 'carbon', 'labels', 'chartKasRW', 'dataPengeluaran'));

    }

}
