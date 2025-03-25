<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KasRT as ModelKasRt;
use App\Models\RT;
use App\Models\Keluarga;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KasRt extends Controller
{
    public function index(Request $request, $nama_RT)
    {
        try {
            $admin = Auth::user();

            // Ambil RT berdasarkan nama_RT
            $rt = RT::where('nama_RT', $nama_RT)->firstOrFail();

            // Cek apakah admin memiliki akses ke RT ini
            if ($admin->rt_id != $rt->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke kas RT ini.',
                ], 403);
            }

            // Ambil kas RT yang sesuai
            $kas = ModelKasRt::where('rt_id', $rt->id)->first();

            return response()->json([
                'success'      => true,
                'kas_rt'       => $kas,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in KasRtController@index: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dalam mengambil data!',
            ], 500);
        }
    }

    public function update(Request $request, $nama_RT)
    {
        try {
            $admin = Auth::user();

            // Ambil RT berdasarkan nama_RT
            $rt = RT::where('nama_RT', $nama_RT)->firstOrFail();

            // Pastikan admin hanya bisa mengelola kas RT miliknya
            if ($admin->rt_id != $rt->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah kas RT ini.',
                ], 403);
            }

            // Ambil semua keluarga dalam RT admin
            $keluargas = Keluarga::where('rt_id', $rt->id)->pluck('no_kk');

            // Hitung total kas RT (hanya keluarga yang membayar >= Rp 25.000)
            $totalKas = Pembayaran::whereIn('no_kk_keluarga', $keluargas)
                ->selectRaw('no_kk_keluarga, year, month, SUM(sejumlah) as total_bayar')
                ->groupBy('no_kk_keluarga', 'year', 'month')
                ->having('total_bayar', '>=', 25000)
                ->get()
                ->count() * 25000;

            // Hitung total pengeluaran kas RT
            $totalPengeluaran = PengeluaranKasRT::where('rt_id', $rt->id)->sum('nominal');

            // Hitung jumlah kas akhir
            $jumlahKasAkhir = $totalKas - $totalPengeluaran;

            // Simpan atau update kas RT
            ModelKasRt::updateOrCreate(
                ['rt_id' => $rt->id],
                ['jumlah_kas_rt' => $jumlahKasAkhir]
            );

            return response()->json([
                'success'       => true,
                'message'       => 'Jumlah kas RT sudah diperbarui!',
                'jumlah_kas_rt' => $jumlahKasAkhir
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in KasRtController@update: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jumlah kas RT!',
            ], 500);
        }
    }
}

