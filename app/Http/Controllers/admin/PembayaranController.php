<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Keluarga;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PembayaranController extends Controller
{
    public function index(Request $request, $nama_RT)
    {
        $admin = Auth::user();

        $year  = $request->input('year', Carbon::now('Asia/Jakarta')->year); // Default ke tahun sekarang
        $month = $request->input('month', Carbon::now('Asia/Jakarta')->month);

        // Validasi bulan, pastikan bulan antara 1 dan 12
        if ($month < 1 || $month > 12) {
            $month = Carbon::now('Asia/Jakarta')->month; // Set ke bulan saat ini jika bulan tidak valid
        }

        // Validasi tahun, jika tidak valid set ke tahun saat ini
        if (! is_numeric($year) || $year < 1900 || $year > Carbon::now('Asia/Jakarta')->year + 3) {
            $year = Carbon::now('Asia/Jakarta')->year; // Set ke tahun saat ini jika tahun tidak valid
        }

        $admin = Auth::user();

        $keluargas = Keluarga::where('rt_id', $admin->rt_id)->pluck('no_kk');

        $pembayarans = Pembayaran::where('year', $year)
            ->where('month', $month)
            ->whereIn('no_kk_keluarga', $keluargas)
            ->with('activityLog')
            ->get();

        $totalPembayaranPerbulan = $pembayarans->sum('sejumlah');

        $keluargas = Keluarga::where('rt_id', $admin->rt_id)->get();

        confirmDelete('Hapus Pembayaran', 'Yakin ingin menghapus pembayaran ini?');

        return view('admin.keluarga.bayar', compact('pembayarans', 'keluargas', 'nama_RT', 'year', 'month', 'totalPembayaranPerbulan'));
    }

    public function store(Request $request, $nama_RT)
    {
        $request->validate([
            'no_kk_keluarga' => 'required|exists:keluargas,no_kk',
            'sejumlah'       => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
            'year'           => 'required|integer',
            'month'          => 'required|integer|min:1|max:12',
        ]);

        $tgl_pembayaran = Carbon::parse($request->tgl_pembayaran);

        // Menyimpan pembayaran
        $pembayaran = Pembayaran::create([
            'no_kk_keluarga' => $request->no_kk_keluarga,
            'sejumlah'       => $request->sejumlah,
            'tgl_pembayaran' => $tgl_pembayaran,
            'year'           => $request->year,
            'month'          => $request->month,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat pembayaran dengan No KK {$pembayaran->no_kk_keluarga} dan jumlah sebesar {$pembayaran->sejumlah}",
            'target_table' => 'pembayarans',
            'target_id'    => $pembayaran->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Pembayaran berhasil ditambahkan.');

        return redirect()->route('admin.pembayaran.index', [
            'nama_RT' => $nama_RT,
            'year'    => $pembayaran->year,
            'month'   => $pembayaran->month,
        ]);
    }

    public function update(Request $request, $nama_RT, Pembayaran $pembayaran)
    {
        $request->validate([
            'sejumlah'       => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
        ]);

        $tgl_pembayaran = Carbon::parse($request->tgl_pembayaran);
        $year           = $tgl_pembayaran->year;
        $month          = $tgl_pembayaran->month;

        $oldData = $pembayaran->replicate();

        // Update pembayaran
        $pembayaran->update([
            'sejumlah'       => $request->sejumlah,
            'tgl_pembayaran' => $tgl_pembayaran,
            'year'           => $year,
            'month'          => $month,
        ]);

        // Catat aktivitas perubahan
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengubah pembayaran dari No KK {$pembayaran->no_kk_keluarga} -> jumlah {$oldData->sejumlah} menjadi {$pembayaran->sejumlah}
                            <br> tanggal {$oldData->tgl_pembayaran} menjadi {$pembayaran->tgl_pembayaran}",
            'target_table' => 'pembayarans',
            'target_id'    => $pembayaran->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Pembayaran berhasil diperbarui.');

        return redirect()->route('admin.pembayaran.index', [
            'nama_RT' => $nama_RT,
            'year'    => $pembayaran->year,
            'month'   => $pembayaran->month,
        ]);
    }

    public function destroy($nama_RT, Pembayaran $pembayaran)
    {
        // Simpan data sebelum dihapus untuk log aktivitas
        $deletedData = $pembayaran->replicate();
        $changes     = [];

        // Cek apakah data pembayaran terkait dengan keluarga
        $keluarga = Keluarga::where('no_kk', $deletedData->no_kk_keluarga)->first();

        if ($keluarga) {
            $changes[] = "Pembayaran dari keluarga dengan No KK {$deletedData->no_kk_keluarga} ({$keluarga->nama_kepala_keluarga}) sebesar Rp " . number_format($deletedData->sejumlah, 0, ',', '.') . " pada {$deletedData->tgl_pembayaran->format('d M Y')} telah dihapus.";
        } else {
            $changes[] = "Pembayaran dengan No KK {$deletedData->no_kk_keluarga} sebesar Rp " . number_format($deletedData->sejumlah, 0, ',', '.') . " pada {$deletedData->tgl_pembayaran->format('d M Y')} telah dihapus.";
        }

        // Hapus pembayaran
        $pembayaran->delete();

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => implode('<br>', $changes),
            'target_table' => 'pembayarans',
            'target_id'    => $deletedData->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Pembayaran berhasil dihapus.');

        return redirect()->route('admin.pembayaran.index', [
            'nama_RT' => $nama_RT,
            'year'    => $deletedData->year,
            'month'   => $deletedData->month,
        ]);
    }
}
