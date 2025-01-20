<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RT;
use Illuminate\Http\Request;

class RtController extends Controller
{
    public function index()
    {
        $rts = RT::orderBy('nama_RT', 'asc')->with(['activityLog.user'])->get();
        return view('superadmin.rt.index', compact('rts'));
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

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'create',
            'description' => "Membuat RT dengan nama {$rts->nama_RT} - {$rts->nama_jalan}",
            'target_table' => 'rts',
            'target_id' => $rts->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.rt.index')->with('success', 'RT berahasil dibuat');

    }

    public function edit(RT $rT)
    {
        return response()->json($rT);
    }

    public function update(Request $request, $id)
    {
        $rts = RT::findOrFail($id);

        $request->validate([
            'nama_RT' => 'required|string|max:255',
            'nama_jalan' => 'required|string|max:255',
        ]);

        $rts->update([
            'nama_RT' => $request->nama_RT,
            'nama_jalan' => $request->nama_jalan,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'update',
            'description' => "Memperbarui RT menjadi : {$rts->nama_RT} - {$rts->nama_jalan}",
            'target_table' => 'rts',
            'target_id' => $rts->id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.rt.index')->with('success', 'RT berhasil diperbarui');
    }

    public function destroy($id)
    {
        $rts = RT::findOrFail($id);

        $nama_RT = $rts->nama_RT;
        $nama_jalan = $rts->nama_jalan;

        $rts->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'delete',
            'description' => "Menghapus data RT dengan nama - {$nama_RT} - {$nama_jalan}",
            'target_table' => 'rts',
            'target_id' => $id,
            'performed_at' => now(),
        ]);

        return redirect()->route('superadmin.rt.index')->with('success', 'RT berhasil dihapus');
    }

}
