<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
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

        // Menghitung rentang 5 bulan sebelum bulan sekarang (tidak termasuk bulan sekarang)
        $currentDate = Carbon::now();
        // Mulai dari 5 bulan yang lalu (awal bulan) hingga 1 bulan yang lalu (akhir bulan)
        $startDate = $currentDate->copy()->subMonths(5)->startOfMonth();
        $endDate   = $currentDate->copy()->subMonth()->endOfMonth();

        // Query pengeluaran berdasarkan bulan dan jumlah total per bulan
        $expenses = PengeluaranKasRw::whereBetween('tgl_pengeluaran', [$startDate, $endDate])
            ->select(DB::raw('MONTH(tgl_pengeluaran) as month'), DB::raw('SUM(nominal) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        // Buat array label dan data untuk 5 bulan tersebut
        $labels = [];
        $data   = [];
        // Misal, kita ambil data dari 5 bulan lalu hingga 1 bulan lalu
        for ($i = 5; $i >= 1; $i--) {
            // Ambil nomor bulan (1-12)
            $monthNumber = $currentDate->copy()->subMonths($i)->format('n');
            // Format label bulan (contoh: "Jan", "Feb", dst.)
            $labels[] = $currentDate->copy()->subMonths($i)->format('M');
            // Ambil total pengeluaran, jika tidak ada data maka 0
            $data[] = isset($expenses[$monthNumber]) ? $expenses[$monthNumber] : 0;
        }

        return view('superadmin.dashboard', compact('kasRw', 'pengeluaran', 'kegiatan', 'carbon', 'labels', 'data'));
    }

}
