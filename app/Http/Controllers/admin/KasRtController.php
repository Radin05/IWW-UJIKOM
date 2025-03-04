<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KasRT;
use App\Models\KegiatanRt;
use App\Models\Keluarga;
use App\Models\Pembayaran;
use App\Models\PengeluaranKasRt;
use App\Models\RT;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KasRTController extends Controller
{
    public function index(Request $request, $nama_RT)
    {
        $admin = Auth::user();

        // Ambil RT yang sesuai dengan nama_RT yang diberikan
        $rt = RT::where('nama_RT', $nama_RT)->firstOrFail();

        $kegiatans = KegiatanRt::orderBy('tanggal_kegiatan', 'desc')->get();

        // Pastikan admin hanya bisa melihat kas RT sesuai dengan RT yang dikelolanya
        if ($admin->rt_id != $rt->id) {
            abort(403, 'Anda tidak memiliki akses ke kas RT ini.');
        }

        // Ambil kas RT yang sesuai dengan RT admin
        $kas = KasRT::where('rt_id', $rt->id)->first();

        $pengeluarans = PengeluaranKasRt::where('rt_id', $rt->id)
            ->orderBy('tgl_pengeluaran', 'asc')
            ->with(['kegiatan', 'activityLog.user'])
            ->get();

        return view('admin.kas_rt.index', compact('nama_RT', 'kas', 'pengeluarans', 'kegiatans'));
    }

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

        $totalKas = Pembayaran::whereIn('no_kk_keluarga', $keluargas)
            ->selectRaw('no_kk_keluarga, year, month, SUM(sejumlah) as total_bayar')
            ->groupBy('no_kk_keluarga', 'year', 'month') // Pastikan per keluarga per bulan dihitung sekali
            ->having('total_bayar', '>=', 25000)         // Pastikan hanya dihitung jika pembayaran lebih dari 25.000
            ->get()
            ->count() * 25000;

        // Ambil total pengeluaran untuk RT tersebut
        $totalPengeluaran = PengeluaranKasRT::where('rt_id', $rt->id)->sum('nominal');

        // Hitung jumlah kas setelah dikurangi pengeluaran
        $jumlahKasAkhir = $totalKas - $totalPengeluaran;

        // Perbarui atau buat kas RT baru jika belum ada
        $kas = KasRT::updateOrCreate(
            ['rt_id' => $rt->id],
            ['jumlah_kas_rt' => $jumlahKasAkhir]
        );

        Alert::success('Berhasil', 'Jumlah kas RT sudah diperbarui!');
        return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
    }

    public function dataPerTahun(Request $request, $nama_RT)
    {
        Alert::success('Berhasil', 'Pengeluaran per tahun telah diperbarui!');
        return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
    }

    public function store(Request $request, $nama_RT)
    {
        $request->validate([
            'nominal'         => 'required|numeric|min:0',
            'kegiatan_id'     => 'nullable|exists:kegiatan_rts,id',
            'keterangan'      => 'nullable|string',
            'tgl_pengeluaran' => 'required|date',
        ]);

        $admin = Auth::user();
        $rt    = RT::where('nama_RT', $nama_RT)->firstOrFail();

        if ($admin->rt_id != $rt->id) {
            abort(403, 'Anda tidak memiliki akses untuk menambah pengeluaran ini.');
        }

        // Ambil kas RT
        $kasRT = KasRT::where('rt_id', $rt->id)->first();

        // Cek apakah kas RT mencukupi untuk pengeluaran
        if (! $kasRT || $kasRT->jumlah_kas_rt <= 0) {
            Alert::error('Gagal', 'Saldo kas RT 0');
            return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
        }

        if ($kasRT->jumlah_kas_rt < $request->nominal) {
            Alert::error('Gagal', 'Pengeluaran melebihi saldo kas RT.');
            return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
        }

        $tgl_pengeluaran = Carbon::parse($request->tgl_pengeluaran);

        // Simpan pengeluaran baru
        $pengeluaran = PengeluaranKasRt::create([
            'nominal'         => $request->nominal,
            'kegiatan_id'     => $request->kegiatan_id ?: null,
            'keterangan'      => $request->keterangan,
            'tgl_pengeluaran' => $tgl_pengeluaran,
            'rt_id'           => $rt->id,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat pengeluaran dengan nominal {$pengeluaran->nominal}",
            'target_table' => 'pengeluaran_kas_rts',
            'target_id'    => $pengeluaran->id,
            'performed_at' => now(),
        ]);

        // Perbarui jumlah kas RT setelah pengeluaran
        $kasRT->jumlah_kas_rt -= $request->nominal;
        $kasRT->save();

        Alert::success('Berhasil', 'Pengeluaran berhasil ditambahkan dan kas RT berhasil diperbarui.');
        return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
    }

    public function updatePengeluaran(Request $request, $nama_RT, $id)
    {
        $request->validate([
            'nominal'         => 'required|numeric|min:0',
            'kegiatan_id'     => 'nullable|exists:kegiatan_rts,id',
            'keterangan'      => 'nullable|string',
            'tgl_pengeluaran' => 'required|date',
        ]);

        $admin = Auth::user();
        $rt    = RT::where('nama_RT', $nama_RT)->firstOrFail();

        // Pastikan admin hanya bisa mengelola kas RT sesuai dengan RT yang dikelolanya
        if ($admin->rt_id != $rt->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate pengeluaran ini.');
        }

        $pengeluaran = PengeluaranKasRt::where('rt_id', $rt->id)->findOrFail($id);
        $kasRT       = KasRT::where('rt_id', $rt->id)->first();

        if (! $kasRT) {
            Alert::error('Gagal', 'Kas RT tidak ditemukan.');
            return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
        }

        // Hitung saldo kas setelah update pengeluaran
        $saldoSetelahUpdate = $kasRT->jumlah_kas_rt + $pengeluaran->nominal - $request->nominal;

        if ($saldoSetelahUpdate < 0) {
            Alert::error('Gagal', 'Pengeluaran melebihi saldo kas RT.');
            return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
        }

        $tgl_pengeluaran = Carbon::parse($request->tgl_pengeluaran);

        // Update pengeluaran
        $pengeluaran->update([
            'nominal'         => $request->nominal,
            'kegiatan_id'     => $request->kegiatan_id ?: null,
            'keterangan'      => $request->keterangan,
            'tgl_pengeluaran' => $tgl_pengeluaran,
        ]);

        // Perbarui jumlah kas RT
        $kasRT->jumlah_kas_rt = $saldoSetelahUpdate;
        $kasRT->save();

        // Catat aktivitas
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengupdate pengeluaran ID {$pengeluaran->id} dengan nominal {$request->nominal}",
            'target_table' => 'pengeluaran_kas_rts',
            'target_id'    => $pengeluaran->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Pengeluaran berhasil diperbarui.');
        return redirect()->route('admin.kas.index', ['nama_RT' => $nama_RT]);
    }

}
