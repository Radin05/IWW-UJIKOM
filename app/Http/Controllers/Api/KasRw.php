<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KasRw as ModelKasRw;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRw;
use Illuminate\Support\Facades\Log;

class KasRw extends Controller
{
    public function index()
    {
        try {
            $kasRw = ModelKasRw::first();

            return response()->json([
                'success' => true,
                'kas_rw'  => $kasRw ?? [],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in KasRw@index: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dalam mengambil data!',
            ], 500);
        }
    }

    public function update($id)
    {
        try {
            $totalKas = Pembayaran::selectRaw('no_kk_keluarga, year, month, SUM(sejumlah) as total_bayar')
                ->groupBy('no_kk_keluarga', 'year', 'month')
                ->get();

            $totalKasRW = 0;

            foreach ($totalKas as $pembayaran) {
                $sisa = max($pembayaran->total_bayar - 25000, 0);
                $totalKasRW += $sisa;
            }

            $totalPengeluaran = PengeluaranKasRw::sum('nominal');
            $jumlahKasAkhir   = $totalKasRW - $totalPengeluaran;

            $kasRw = ModelKasRw::findOrFail($id);
            $kasRw->update(['jumlah_kas_rw' => $jumlahKasAkhir]);

            return response()->json([
                'success'       => true,
                'message'       => 'Jumlah kas RW sudah diperbarui!',
                'jumlah_kas_rw' => $jumlahKasAkhir,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in KasRwr@update: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jumlah kas RW!',
            ], 500);
        }
    }

}
