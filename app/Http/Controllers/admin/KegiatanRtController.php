<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KegiatanRt;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KegiatanRtController extends Controller
{
    public function index($nama_RT)
    {
        $kegiatan = KegiatanRt::orderBy('tanggal_kegiatan', 'desc')->with(['activityLog.user'])->get();

        confirmDelete('Hapus Data Kegiatan', 'Yakin ingin menghapus data kegiatan ini?');

        return view('admin.kegiatan.index', compact('kegiatan', 'nama_RT'));
    }

    public function store(Request $request, $nama_RT)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'jam_kegiatan'     => 'required|date_format:H:i',
            'status'           => 'required',
        ]);

        KegiatanRt::create($request->all());

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat kegiatan Rt",
            'target_table' => 'kegiatan_rts',
            'target_id'    => KegiatanRt::max('id'),
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Kegiatan Rt berhasil ditambahkan.');

        return redirect()->route('admin.kegiatan.index', ['nama_RT' => $nama_RT]);
    }

    public function update(Request $request, $nama_RT, $id)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'jam_kegiatan'     => 'required',
            'status'           => 'required',
        ]);

        $kegiatan = KegiatanRt::findOrFail($id);
        $kegiatan->update($request->all());

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'update',
            'description'  => "Mengupdate kegiatan Rt",
            'target_table' => 'kegiatan_rts',
            'target_id'    => $kegiatan->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Kegiatan Rt berhasil diperbarui.');

        return redirect()->route('admin.kegiatan.index', ['nama_RT' => $nama_RT])->with('success', 'Kegiatan Rt berhasil diperbarui.');
    }

    public function destroy($nama_RT, $id)
    {
        $kegiatan = KegiatanRt::findOrFail($id);
        $kegiatan->delete();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => "Menghapus kegiatan Rt",
            'target_table' => 'kegiatan_rts',
            'target_id'    => $kegiatan->id,
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Kegiatan Rt berhasil dihapus.');

        return redirect()->route('admin.kegiatan.index', ['nama_RT' => $nama_RT])->with('success', 'Kegiatan Rt berhasil dihapus.');
    }

}
