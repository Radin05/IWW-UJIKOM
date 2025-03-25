<?php
namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RT;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RtController extends Controller
{
    public function index()
    {
        $rts = RT::orderBy('nama_RT', 'asc')->with(['activityLog.user'])->get();

        confirmDelete('Hapus RT?', 'Jika dihapus, semua data yang terkait akan terhapus juga');

        return view('operator.rt.index', compact('rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_RT' => 'required|string|max:255',
        ]);

        $rts = RT::create([
            'nama_RT' => $request->nama_RT,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'create',
            'description'  => "Membuat RT dengan nama {$rts->nama_RT}",
            'target_table' => 'rts',
            'target_id'    => $rts->id,
            'performed_at' => now(),
        ]);

        if ($request->has('additional_fields_nr')) {
            foreach ($request->additional_fields_nr as $index => $additionalFieldNr) {
                if (!empty($additionalFieldNr)) {
                    $newRt          = new RT();
                    $newRt->nama_RT = $additionalFieldNr;
                    $newRt->save();

                    // Simpan log untuk setiap RT tambahan
                    ActivityLog::create([
                        'user_id'      => auth()->id(),
                        'activity'     => 'create',
                        'description'  => "Membuat RT tambahan dengan nama {$newRt->nama_RT}",
                        'target_table' => 'rts',
                        'target_id'    => $newRt->id,
                        'performed_at' => now(),
                    ]);
                }
            }
        }

        Alert::success('Success', 'Data RT Berhasil Di Tambahkan');

        return redirect()->route('operator.rt.index');
    }

    public function destroy($id)
    {
        $rts     = RT::findOrFail($id);
        $nama_RT = $rts->nama_RT;

        $rts->delete();

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'activity'     => 'delete',
            'description'  => "Menghapus data {$nama_RT}",
            'target_table' => 'rts',
            'target_id'    => $id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Data RT' . $rts->id . ' Berhasil Di Hapus');
        return redirect()->route('operator.rt.index');
    }

}
