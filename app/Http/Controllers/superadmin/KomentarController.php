<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\KegiatanRw;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function index()
    {
        $now               = now();
        $kegiatanMendatang = KegiatanRw::where(function ($query) use ($now) {
            $query->where('tanggal_kegiatan', '>', $now)
                ->orWhere('jam_kegiatan', '>', $now);
        })
            ->orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('jam_kegiatan', 'asc')
            ->get();

        $komentars = Komentar::with('user')->latest()->get();

        return view('superadmin.komentar.index', compact('komentars', 'kegiatanMendatang'));
    }

    public function getByKegiatan(Request $request)
    {
        $komentars = Komentar::where('kegiatan_id', $request->kegiatan_id)
            ->with('user') // Menampilkan data user terkait komentar
            ->get();

        return response()->json([
            'komentars' => $komentars,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'konten' => 'required|string|max:500',
        ]);

        $komentar = Komentar::create([
            'user_id'      => Auth::id(),
            'konten'       => $request->konten,
            'performed_at' => now(),
        ]);

        return response()->json([
            'message'  => 'Komentar berhasil ditambahkan',
            'komentar' => $komentar->load('user'),
        ]);
    }

    public function update(Request $request, Komentar $komentar)
    {
        if ($komentar->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengedit komentar ini'], 403);
        }

        $request->validate([
            'konten' => 'required|string|max:500',
        ]);

        $komentar->update([
            'konten'       => $request->konten,
            'performed_at' => now(),
        ]);

        return response()->json([
            'message'  => 'Komentar berhasil diperbarui',
            'komentar' => $komentar,
        ]);
    }

    public function destroy(Komentar $komentar)
    {
        if ($komentar->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'], 403);
        }

        $komentar->delete();

        return response()->json([
            'message' => 'Komentar berhasil dihapus',
        ]);
    }

}
