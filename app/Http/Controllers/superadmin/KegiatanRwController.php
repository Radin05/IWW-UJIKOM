<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KegiatanRw;
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
        $validated = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'jam_kegiatan'     => 'required|date_format:H:i',
            'status'           => 'required|string',
        ]);

        $kegiatan = KegiatanRw::create($validated);

        // Tambahkan ke Activity Log
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'target_table' => 'kegiatan_rws',
            'target_id'    => $kegiatan->id,
            'description'  => "Menambahkan kegiatan RW: {$kegiatan->nama_kegiatan} pada tanggal {$kegiatan->tanggal_kegiatan} jam {$kegiatan->jam_kegiatan}.",
            'performed_at' => now(),
        ]);

        Alert::success('Berhasil', 'Kegiatan RW berhasil ditambahkan.');

        return redirect()->route('superadmin.kegiatan-rw.index');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'jam_kegiatan'     => 'required|date_format:H:i',
            'status'           => 'required|string',
        ]);

        $kegiatan = KegiatanRw::findOrFail($id);
        $oldData  = $kegiatan->only(['nama_kegiatan', 'deskripsi', 'tanggal_kegiatan', 'jam_kegiatan', 'status']);
        $changes  = [];

        // Cek perubahan data
        if ($oldData['nama_kegiatan'] !== $validated['nama_kegiatan']) {
            $changes[] = "Nama kegiatan dari <strong>{$oldData['nama_kegiatan']}</strong> menjadi <strong>{$validated['nama_kegiatan']}</strong>";
        }
        if ($oldData['deskripsi'] !== $validated['deskripsi']) {
            $changes[] = "Deskripsi diperbarui.";
        }
        if ($oldData['tanggal_kegiatan'] !== $validated['tanggal_kegiatan']) {
            $changes[] = "Tanggal dari <strong>{$oldData['tanggal_kegiatan']}</strong> menjadi <strong>{$validated['tanggal_kegiatan']}</strong>";
        }
        if ($oldData['jam_kegiatan'] !== $validated['jam_kegiatan']) {
            $changes[] = "Jam dari <strong>{$oldData['jam_kegiatan']}</strong> menjadi <strong>{$validated['jam_kegiatan']}</strong>";
        }
        if ($oldData['status'] !== $validated['status']) {
            $changes[] = "Status dari <strong>{$oldData['status']}</strong> menjadi <strong>{$validated['status']}</strong>";
        }

        // Update data kegiatan
        $kegiatan->update($validated);

        // Simpan log aktivitas hanya jika ada perubahan
        if (! empty($changes)) {
            ActivityLog::create([
                'user_id'      => auth()->id(),
                'activity'     => 'update',
                'target_table' => 'kegiatan_rws',
                'target_id'    => $kegiatan->id,
                'description'  => implode('<br>', $changes),
                'performed_at' => now(),
            ]);
        }

        Alert::success('Berhasil', 'Kegiatan RW berhasil diperbarui.');

        return redirect()->route('superadmin.kegiatan-rw.index');
    }

    public function destroy($id)
    {
        $kegiatan = KegiatanRw::findOrFail($id);

        // Tambahkan ke Activity Log sebelum dihapus
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'target_table' => 'kegiatan_rws',
            'target_id'    => $kegiatan->id,
            'description'  => "Menghapus kegiatan RW: {$kegiatan->nama_kegiatan} yang dijadwalkan pada {$kegiatan->tanggal_kegiatan}.",
            'performed_at' => now(),
        ]);

        $kegiatan->delete();

        Alert::success('Berhasil', 'Kegiatan RW berhasil dihapus.');

        return redirect()->route('superadmin.kegiatan-rw.index');
    }

}
