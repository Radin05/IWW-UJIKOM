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
            'nama_jalan' => 'required|string|max:255',
        ]);

        $rts = RT::create([
            'nama_RT' => $request->nama_RT,
            'nama_jalan' => $request->nama_jalan,
        ]);

        if ($request->has('additional_fields_nr') && $request->has('additional_fields_nj')) {
            foreach ($request->additional_fields_nr as $index => $additionalFieldNr) {
                if (!empty($additionalFieldNr)) {
                    $newRt = new RT();
                    $newRt->nama_RT = $additionalFieldNr;

                    // Sesuaikan nama_jalan dengan index yang sama dari additional_fields_nj
                    if (isset($request->additional_fields_nj[$index])) {
                        $newRt->nama_jalan = $request->additional_fields_nj[$index];
                    }

                    $newRt->save();  // Menyimpan data RT yang lengkap
                }
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'create',
            'description' => "Membuat RT dengan nama {$rts->nama_RT} - {$rts->nama_jalan}",
            'target_table' => 'rts',
            'target_id' => $rts->id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Data RT Berhasil Di Tambahkan');

        return redirect()->route('operator.rt.index');

    }

    // public function edit(RT $rT)
    // {
    //     return response()->json($rT);
    // }

    // public function update(Request $request, $id)
    // {
    //     $rts = RT::findOrFail($id);

    //     $request->validate([
    //         'nama_RT' => 'required|string|max:255',
    //         'nama_jalan' => 'required|string|max:255',
    //     ]);

    //     $rts->update([
    //         'nama_RT' => $request->nama_RT,
    //         'nama_jalan' => $request->nama_jalan,
    //     ]);

    //     ActivityLog::create([
    //         'user_id' => auth()->id(),
    //         'activity' => 'update',
    //         'description' => "Memperbarui RT menjadi : {$rts->nama_RT} - {$rts->nama_jalan}",
    //         'target_table' => 'rts',
    //         'target_id' => $rts->id,
    //         'performed_at' => now(),
    //     ]);

    //     Alert::success('Success', 'Data RT '. $rts->id .' Berhasil Di Ubah');

    //     return redirect()->route('superadmin.rt.index');
    // }

    public function destroy($id)
    {
        $rts = RT::findOrFail($id);
        $nama_RT = $rts->nama_RT;

        $nama_jalan = $rts->nama_jalan;

        $rts->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'delete',
            'description' => "Menghapus data {$nama_RT} - {$nama_jalan}",
            'target_table' => 'rts',
            'target_id' => $id,
            'performed_at' => now(),
        ]);

        Alert::success('Success', 'Data RT'. $rts->id .' Berhasil Di Hapus');
        return redirect()->route('operator.rt.index');
    }

}
