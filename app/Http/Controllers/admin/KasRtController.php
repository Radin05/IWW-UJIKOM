<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KasRT;
use App\Models\Pembayaran;
use App\Models\Keluarga;
use App\Models\RT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasRTController extends Controller
{
    /**
     * Menampilkan halaman kas RT berdasarkan RT yang dikelola admin.
     */
    public function index($nama_RT)
    {
        $admin = Auth::user();

        // Ambil RT yang sesuai dengan nama_RT yang diberikan
        $rt = RT::where('nama_RT', $nama_RT)->firstOrFail();

        // Pastikan admin hanya bisa melihat kas RT sesuai dengan RT yang dikelolanya
        if ($admin->rt_id != $rt->id) {
            abort(403, 'Anda tidak memiliki akses ke kas RT ini.');
        }

        // Ambil kas RT yang sesuai dengan RT admin
        $kas = KasRT::where('rt_id', $rt->id)->first();

        return view('admin.kas_rt.index', compact('nama_RT', 'kas'));
    }

    /**
     * Memperbarui jumlah kas RT berdasarkan pembayaran yang valid (> 25000 per keluarga per bulan).
     */
    public function update(Request $request, $nama_RT)
    {
        $admin = Auth::user();

        // Ambil RT yang sesuai dengan nama_RT yang diberikan
        $rt = RT::where('nama_RT', $nama_RT)->firstOrFail();

        // Pastikan admin hanya bisa mengelola kas RT sesuai dengan RT yang dikelolanya
        if ($admin->rt_id != $rt->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kas RT ini.');
        }

        // Ambil semua keluarga dalam RT admin
        $keluargas = Keluarga::where('rt_id', $rt->id)->pluck('no_kk');

        // Hitung total kas RT (hanya 25.000 per keluarga per bulan yang dihitung)
        $totalKas = Pembayaran::whereIn('no_kk_keluarga', $keluargas)
            ->where('sejumlah', '>', 25000)
            ->select('no_kk_keluarga', 'year', 'month')
            ->groupBy('no_kk_keluarga', 'year', 'month') // Mengelompokkan berdasarkan keluarga & bulan
            ->get()
            ->count() * 25000;

        // Perbarui atau buat kas RT baru jika belum ada
        $kas = KasRT::updateOrCreate(
            ['rt_id' => $rt->id],
            ['jumlah_kas_rt' => $totalKas]
        );

        return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT])
            ->with('success', 'Jumlah Kas RT berhasil diperbarui!');
    }
}
