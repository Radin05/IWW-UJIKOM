<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KasRw;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRw;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KasRwController extends Controller
{
    public function index()
    {
        $kasRw        = KasRw::first();
        $pengeluarans = PengeluaranKasRw::orderBy('tgl_pengeluaran', 'asc')->with(['activityLog.user'])->get();

        return view('superadmin.kas_rw.index', compact('kasRw', 'pengeluarans'));
    }

    public function update()
    {
        // Ambil total pembayaran per keluarga per bulan
        $totalKas = Pembayaran::selectRaw('no_kk_keluarga, year, month, SUM(sejumlah) as total_bayar')
            ->groupBy('no_kk_keluarga', 'year', 'month')
            ->get();

        // Hitung total yang masuk ke Kas RW (sisa setelah Kas RT mengambil Rp 25.000 per keluarga per bulan)
        $totalKasRW = 0;

        foreach ($totalKas as $pembayaran) {
            $sisa = max($pembayaran->total_bayar - 25000, 0); // Jangan sampai negatif
            $totalKasRW += $sisa;
        }

        // Ambil total pengeluaran RW
        $totalPengeluaran = PengeluaranKasRw::sum('nominal');

        // Hitung jumlah kas RW setelah dikurangi pengeluaran
        $jumlahKasAkhir = $totalKasRW - $totalPengeluaran;

        // Perbarui atau buat kas RW baru jika belum ada
        KasRw::updateOrCreate(
            [],
            ['jumlah_kas_rw' => $jumlahKasAkhir]
        );

        Alert::success('Berhasil', 'Jumlah kas RW sudah diperbarui!');
        return redirect()->route('superadmin.kas.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal'         => 'required|numeric|min:0',
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
        $year            = $tgl_pengeluaran->year;

        // Simpan pengeluaran baru
        $pengeluaran = PengeluaranKasRw::create([
            'nominal'         => $request->nominal,
            'keterangan'      => $request->keterangan,
            'tgl_pengeluaran' => $tgl_pengeluaran,
            'year'            => $year,
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
        $year            = $tgl_pengeluaran->year;

        $pengeluaran->update([
            'nominal'         => $request->nominal,
            'keterangan'      => $request->keterangan,
            'tgl_pengeluaran' => $tgl_pengeluaran,
            'year'            => $year,
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

}
