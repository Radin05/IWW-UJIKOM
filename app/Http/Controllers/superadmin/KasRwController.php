<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HistoriKasRW;
use App\Models\KasRw;
use App\Models\KegiatanRw;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRw;
use App\Models\UangTambahan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KasRwController extends Controller
{
    public function index()
    {
        $kasRw        = KasRw::with('activityLog')->first();
        $kegiatans    = KegiatanRw::orderBy('tanggal_kegiatan', 'desc')->get();
        $pengeluarans = PengeluaranKasRw::orderBy('tgl_pengeluaran', 'asc')
            ->with(['kegiatan', 'activityLog.user'])
            ->paginate(7);
        $totalPengeluaranRw = PengeluaranKasRw::sum('nominal');
        $uangTambahans      = UangTambahan::orderBy('created_at', 'desc')
            ->with('activityLog.user')
            ->paginate(7);
        $riwayatKas = HistoriKasRW::orderBy('created_at', 'desc')
            ->paginate(3);

        return view('superadmin.kas_rw.index', compact('kasRw', 'pengeluarans', 'totalPengeluaranRw', 'kegiatans', 'uangTambahans', 'riwayatKas'));
    }

    public function update()
    {
        // Ambil kas RW sebelum diperbarui
        $kasRwSebelumnya     = KasRw::first();
        $jumlahKasSebelumnya = $kasRwSebelumnya ? $kasRwSebelumnya->jumlah_kas_rw : 0;

        // Ambil semua pembayaran yang sudah dihitung dalam Kas RT (per keluarga per bulan)
        $totalKas = Pembayaran::selectRaw('no_kk_keluarga, year, month, SUM(sejumlah) as total_bayar')
            ->groupBy('no_kk_keluarga', 'year', 'month')
            ->get();

        // Hitung total sisa yang masuk ke Kas RW
        $totalKasRW = 0;

        foreach ($totalKas as $pembayaran) {
            $sisa = max($pembayaran->total_bayar - 25000, 0); // Pastikan tidak negatif
            $totalKasRW += $sisa;
        }

        $uangTambahan = UangTambahan::sum('nominal');

        $jumlahKasHistori = $totalKasRW + $uangTambahan;

        // Simpan ke tabel histori kas
        HistoriKasRW::create([
            'nominal'           => $jumlahKasHistori,
            'tgl_pembaruan_kas' => now()->toDateString(),
        ]);

        $totalPengeluaran = PengeluaranKasRw::sum('nominal');

        // Hitung jumlah kas RW setelah dikurangi pengeluaran
        $jumlahKasAkhir = $jumlahKasHistori - $totalPengeluaran;

        $kasRw                = KasRw::firstOrNew([]);
        $kasRw->jumlah_kas_rw = $jumlahKasAkhir;
        $kasRw->save();

        // Tambahkan ke Activity Log
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'target_table' => 'kas_rw',
            'target_id'    => 1, // Karena Kas RW hanya ada satu data
            'description'  => "Jumlah kas RW diperbarui dari Rp " . number_format($jumlahKasSebelumnya, 0, ',', '.') . " menjadi Rp " . number_format($jumlahKasAkhir, 0, ',', '.'),
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Jumlah kas RW sudah diperbarui!');
        return redirect()->route('superadmin.kas.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal'         => 'required|numeric|min:0',
            'kegiatan_id'     => 'nullable|exists:kegiatan_rws,id',
            'keterangan'      => 'nullable|string',
            'tgl_pengeluaran' => 'required|date',
        ]);

        $kasRW = KasRw::first();

        // Pastikan saldo Kas RW cukup sebelum pengeluaran
        if (! $kasRW || $kasRW->jumlah_kas_rw <= 0) {
            Alert::error('Gagal', 'Saldo kas RW 0');
            return redirect()->route('superadmin.kas.index');
        }

        if ($kasRW->jumlah_kas_rw < $request->nominal) {
            Alert::error('Gagal', 'Pengeluaran melebihi saldo kas RW.');
            return redirect()->route('superadmin.kas.index');
        }

        $tgl_pengeluaran = Carbon::parse($request->tgl_pengeluaran);

        // Simpan pengeluaran baru
        $pengeluaran = PengeluaranKasRw::create([
            'nominal'         => $request->nominal,
            'kegiatan_id'     => $request->kegiatan_id ?: null,
            'keterangan'      => $request->keterangan,
            'tgl_pengeluaran' => $tgl_pengeluaran,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat pengeluaran dengan nominal {$pengeluaran->nominal}",
            'target_table' => 'pengeluaran_kas_rws',
            'target_id'    => $pengeluaran->id,
            'performed_at' => now(),
        ]);

        // Perbarui jumlah kas RT setelah pengeluaran
        $kasRW->jumlah_kas_rw -= $request->nominal;
        $kasRW->save();

        Alert::success('Berhasil', 'Pengeluaran berhasil ditambahkan dan kas RT berhasil diperbarui.');
        return redirect()->route('superadmin.kas.index');
    }

    public function updatePengeluaran(Request $request, $id)
    {
        $request->validate([
            'nominal'         => 'required|numeric|min:0',
            'kegiatan_id'     => 'nullable|exists:kegiatan_rws,id',
            'keterangan'      => 'nullable|string',
            'tgl_pengeluaran' => 'required|date',
        ]);

        $pengeluaran = PengeluaranKasRw::find($id);
        $kasRW       = KasRw::first();

        if (! $kasRW) {
            Alert::error('Gagal', 'Kas RW tidak ditemukan.');
            return redirect()->route('superadmin.kas.index');
        }

        $saldoSetelahUpdate = $kasRW->jumlah_kas_rw + $pengeluaran->nominal - $request->nominal;

        if ($saldoSetelahUpdate < 0) {
            Alert::error('Gagal', 'Pengeluaran melebihi saldo kas RW.');
            return redirect()->route('superadmin.kas.index');
        }

        $tgl_pengeluaran = Carbon::parse($request->tgl_pengeluaran);

        $pengeluaran->update([
            'nominal'         => $request->nominal,
            'kegiatan_id'     => $request->kegiatan_id ?: null,
            'keterangan'      => $request->keterangan,
            'tgl_pengeluaran' => $tgl_pengeluaran,
        ]);

        $kasRW->jumlah_kas_rw = $saldoSetelahUpdate;
        $kasRW->save();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengupdate pengeluaran ID {$pengeluaran->id} dengan nominal {$request->nominal}",
            'target_table' => 'pengeluaran_kas_rws',
            'target_id'    => $pengeluaran->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Pengeluaran berhasil diperbarui.');
        return redirect()->route('superadmin.kas.index');
    }

    public function destroyPengeluaran($id)
    {
        $pengeluaran = PengeluaranKasRw::find($id);
        $kasRW       = KasRw::first();

        if (! $pengeluaran) {
            Alert::error('Gagal', 'Pengeluaran tidak ditemukan.');
            return redirect()->route('superadmin.kas.index');
        }

        if (! $kasRW) {
            Alert::error('Gagal', 'Kas RW tidak ditemukan.');
            return redirect()->route('superadmin.kas.index');
        }

        // Kembalikan saldo sebelum menghapus
        $kasRW->jumlah_kas_rw += $pengeluaran->nominal;
        $kasRW->save();

        // Simpan aktivitas
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => "Menghapus pengeluaran ID {$pengeluaran->id} dengan nominal {$pengeluaran->nominal}",
            'target_table' => 'pengeluaran_kas_rws',
            'target_id'    => $pengeluaran->id,
            'performed_at' => now(),
        ]);

        $pengeluaran->delete();

        Alert::success('Berhasil', 'Pengeluaran berhasil dihapus.');
        return redirect()->route('superadmin.kas.index');
    }

    public function storeUangTambahan(Request $request)
    {
        $request->validate([
            'nominal'    => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan transaksi uang tambahan ke tabel uang_tambahans
        $uangTambahan = UangTambahan::create([
            'nominal'    => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        // Ambil record Kas RW (asumsikan hanya ada satu record)
        $kasRw = KasRw::first();
        if (! $kasRw) {
            // Jika belum ada, buat record baru dengan jumlah awal 0
            $kasRw = KasRw::create([
                'jumlah_kas_rw' => 0,
            ]);
        }

        // Simpan jumlah kas sebelum update
        $jumlahKasSebelumnya = $kasRw->jumlah_kas_rw;

        // Update kas_rw:
        // - Tambahkan nominal uang tambahan ke jumlah kas RW
        // - Set foreign key uang_tambahan_kas_id ke transaksi uang tambahan yang baru dibuat
        $kasRw->jumlah_kas_rw += $uangTambahan->nominal;
        $kasRw->uang_tambahan_kas_id = $uangTambahan->id;
        $kasRw->save();

        // Tambahkan ke Activity Log
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'target_table' => 'uang_tambahans',
            'target_id'    => $uangTambahan->id,
            'description'  => "Menambahkan uang tambahan sebesar Rp " . number_format($uangTambahan->nominal, 0, ',', '.') .
            ". Kas RW sebelum: Rp " . number_format($jumlahKasSebelumnya, 0, ',', '.') .
            ", setelah: Rp " . number_format($kasRw->jumlah_kas_rw, 0, ',', '.'),
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Uang tambahan berhasil ditambahkan dan kas RW diperbarui.');
        return redirect()->route('superadmin.kas.index');
    }

    public function updateUangTambahan(Request $request, $id)
    {
        $request->validate([
            'nominal'    => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $uangTambahan        = UangTambahan::findOrFail($id);
        $jumlahKasSebelumnya = KasRw::first()->jumlah_kas_rw;

        // Hitung selisih perubahan
        $selisih = $request->nominal - $uangTambahan->nominal;

        // Update data uang tambahan
        $uangTambahan->update([
            'nominal'    => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        // Update jumlah kas RW
        $kasRw = KasRw::first();
        $kasRw->jumlah_kas_rw += $selisih;
        $kasRw->save();

        // Catat di Activity Log
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'target_table' => 'uang_tambahans',
            'target_id'    => $uangTambahan->id,
            'description'  => "Mengubah uang tambahan menjadi Rp " . number_format($uangTambahan->nominal, 0, ',', '.') .
            ". Kas RW sebelumnya: Rp " . number_format($jumlahKasSebelumnya, 0, ',', '.') .
            ", setelah: Rp " . number_format($kasRw->jumlah_kas_rw, 0, ',', '.'),
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Uang tambahan berhasil diperbarui.');
        return redirect()->route('superadmin.kas.index');
    }

    public function destroyUangTambahan($id)
    {
        $uangTambahan = UangTambahan::findOrFail($id);
        $kasRw        = KasRw::where('uang_tambahan_kas_id', $id)->first(); // Ambil instance KasRw

        if ($kasRw) {
            $jumlahKasSebelumnya = $kasRw->jumlah_kas_rw;

            // Kurangi nominal uang tambahan dari jumlah kas RW
            $kasRw->jumlah_kas_rw -= $uangTambahan->nominal;
            $kasRw->uang_tambahan_kas_id = null; // Set foreign key ke NULL
            $kasRw->save();
        }

        // Catat di Activity Log sebelum menghapus
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'target_table' => 'uang_tambahans',
            'target_id'    => $uangTambahan->id,
            'description'  => "Menghapus uang tambahan sebesar Rp " . number_format($uangTambahan->nominal, 0, ',', '.') .
            ". Kas RW sebelumnya: Rp " . number_format($jumlahKasSebelumnya, 0, ',', '.') .
            ", setelah: Rp " . number_format($kasRw->jumlah_kas_rw, 0, ',', '.'),
            'performed_at' => now(),
        ]);

        // Hapus data uang tambahan
        $uangTambahan->delete();

        Alert::success('Berhasil', 'Uang tambahan berhasil dihapus.');
        return redirect()->route('superadmin.kas.index');
    }

}
