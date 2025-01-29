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

        $pembayarans = Pembayaran::whereYear('tgl_pembayaran', $year)
            ->whereMonth('tgl_pembayaran', $month)
            ->whereIn('no_kk_keluarga', $keluargas)
            ->get();

        $totalPembayaranFiltered = $pembayarans->sum('sejumlah');

        $keluargas = Keluarga::where('rt_id', $admin->rt_id)->get();

        return view('admin.keluarga.bayar', compact('pembayarans', 'keluargas', 'nama_RT', 'year', 'month', 'totalPembayaranFiltered'));
    }

    public function store(Request $request, $nama_RT)
    {
        $request->validate([
            'no_kk_keluarga' => 'required|exists:keluargas,no_kk',
            'sejumlah'       => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
        ]);

        $tgl_pembayaran = Carbon::parse($request->tgl_pembayaran);
        $year           = $tgl_pembayaran->year;
        $month          = $tgl_pembayaran->month;

        // Menyimpan pembayaran
        $pembayaran = Pembayaran::create([
            'no_kk_keluarga' => $request->no_kk_keluarga,
            'sejumlah'       => $request->sejumlah,
            'tgl_pembayaran' => $tgl_pembayaran,
            'year'           => $year,
            'month'          => $month,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat pembayaran dengan No KK {$pembayaran->no_kk_keluarga} dan jumlah sebesar {$pembayaran->sejumlah}",
            'target_table' => 'pembayarans',
            'target_id'    => $pembayaran->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('admin.pembayaran.index', [
            'nama_RT' => $nama_RT,
            'year'    => $pembayaran->year,
            'month'   => $pembayaran->month,
        ])->with('success', 'Data pembayaran berhasil disimpan!');
    }

    public function show(Pembayaran $pembayaran)
    {
        //
    }

    public function edit(Pembayaran $pembayaran)
    {
        //
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        //
    }

    public function destroy(Pembayaran $pembayaran)
    {
        //
    }
}
