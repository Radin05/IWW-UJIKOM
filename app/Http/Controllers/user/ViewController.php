<?php
namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\KasRt;
use App\Models\KasRw;
use App\Models\KegiatanRw;
use App\Models\Keluarga;
use App\Models\PengeluaranKasRt;
use App\Models\PengeluaranKasRw;
use App\Models\RT;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ViewController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now('Asia/Jakarta')->year);

        $kasRw       = KasRw::first();
        $jumlahrt    = RT::count();
        $jumlahwarga = Keluarga::count();

        $kegiatanRw = KegiatanRw::orderBy('tanggal_kegiatan', 'desc')->get();

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil RT ID berdasarkan no_kk_keluarga dari user
        $rt_id = Keluarga::where('no_kk', $user->no_kk_keluarga)->value('rt_id') ?? null;

        $jumlahWargaRt = $rt_id ? Keluarga::where('rt_id', $rt_id)->count() : 0;

        $kasRt = KasRt::where('rt_id', $rt_id)->first();

        $superadmins = User::where('role', 'superadmin')->get();

        return view('user.index', compact('year', 'jumlahrt', 'jumlahwarga', 'jumlahWargaRt', 'rt_id', 'kegiatanRw', 'kasRw', 'kasRt', 'superadmins'));
    }

    public function iuran(Request $request)
    {

        // Ambil bulan dan tahun dari request, default ke bulan & tahun saat ini
        $year  = $request->input('year', Carbon::now('Asia/Jakarta')->year);
        $month = $request->input('month', Carbon::now('Asia/Jakarta')->month);

        // Ambil semua RT yang ada
        $rts = RT::all();

        // Ambil kas RT untuk setiap RT
        $kasRts = KasRt::with('rt')->get();

        $pengeluarans = PengeluaranKasRt::with('kegiatan')
            ->whereYear('tgl_pengeluaran', $year)
            ->whereMonth('tgl_pengeluaran', $month)
            ->orderBy('tgl_pengeluaran', 'asc')
            ->get();

        // Validasi bulan (1-12)
        if ($month < 1 || $month > 12) {
            $month = Carbon::now('Asia/Jakarta')->month;
        }

        // Validasi tahun (dari 1900 hingga 3 tahun ke depan)
        if (! is_numeric($year) || $year < 1900 || $year > Carbon::now('Asia/Jakarta')->year + 3) {
            $year = Carbon::now('Asia/Jakarta')->year;
        }

        $kasRw = KasRw::first();

        // Ambil total kas RW berdasarkan bulan & tahun
        // **Hitung jumlah kas RW kumulatif hingga bulan yang dipilih**
        $jumlah_kas_rw = KasRw::whereYear('updated_at', '<=', $year)
            ->where(function ($query) use ($year, $month) {
                $query->whereYear('updated_at', '<', $year)
                    ->orWhere(function ($subquery) use ($month, $year) {
                        $subquery->whereYear('updated_at', $year)
                            ->whereMonth('updated_at', '<=', $month);
                    });
            })
            ->sum('jumlah_kas_rw');

        // Ambil pengeluaran berdasarkan bulan & tahun
        $pengeluaranRw = PengeluaranKasRw::with('kegiatan')
            ->whereYear('tgl_pengeluaran', $year)
            ->whereMonth('tgl_pengeluaran', $month)
            ->orderBy('tgl_pengeluaran', 'asc')
            ->get();

        // Tanggal pembaruan terakhir
        $tgl_pembaruan = KasRw::whereYear('updated_at', '<=', $year)
            ->whereMonth('updated_at', '<=', $month)
            ->latest('updated_at')
            ->value('updated_at');

        $tgl_pembaruan = $tgl_pembaruan ? Carbon::parse($tgl_pembaruan)->format('d-m-Y') : 'Belum diperbarui';

        return view('user.iuran', compact('rts', 'pengeluarans', 'kasRts', 'kasRw', 'jumlah_kas_rw', 'pengeluaranRw', 'year', 'month', 'tgl_pembaruan'));
    }

    public function profil()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil data keluarga berdasarkan no_kk pengguna
        $keluarga = Keluarga::where('no_kk', $user->no_kk_keluarga)->first();

        return view('user.profil', compact('user', 'keluarga'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_keluarga' => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user        = Auth::user();
        $user->email = $request->email;
        $user->save(); // Simpan perubahan pada user

        // Cek apakah user memiliki data keluarga
        if ($user->keluarga) {
            $keluarga                = $user->keluarga; // Ambil data keluarga dari relasi
            $keluarga->nama_keluarga = $request->nama_keluarga;
            $keluarga->save();
        }

        return redirect()->route('warga.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user           = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('warga.profil')->with('success', 'Password berhasil diperbarui!');
    }
}
