<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\KegiatanRw;
use App\Models\Komentar;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KegiatanRwController extends Controller
{
    public function index()
    {
        $kegiatan = KegiatanRw::orderBy('tanggal_kegiatan', 'desc')->with(['activityLog.user'])->get();

        confirmDelete('Hapus Kegiatan Ini', 'Yakin ingin menghapus data kegiatan ini?');

        return view('superadmin.kegiatan.index', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'jam_kegiatan'     => 'required|date_format:H:i',
            'status'           => 'required',
        ]);

        KegiatanRw::create($request->all());

        Alert::success('Berhasil', 'Kegiatan RW berhasil ditambahkan.');

        return redirect()->route('superadmin.kegiatan-rw.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'jam_kegiatan'     => 'required',
            'status'           => 'required',
        ]);

        $kegiatan = KegiatanRw::findOrFail($id);
        $kegiatan->update($request->all());

        Alert::success('Berhasil', 'Kegiatan RW berhasil diperbarui.');

        return redirect()->route('superadmin.kegiatan-rw.index');
    }

    public function destroy($id)
    {
        $kegiatan = KegiatanRw::findOrFail($id);
        $kegiatan->delete();

        Alert::success('Berhasil', 'Kegiatan RW berhasil dihapus.');

        return redirect()->route('superadmin.kegiatan-rw.index');
    }

}
