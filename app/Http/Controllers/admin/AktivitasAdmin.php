<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RT;

class AktivitasAdmin extends Controller
{
    public function index($nama_RT)
    {

        $rt = RT::where('nama_RT', $nama_RT)->firstOrFail();

        $activityLogs = ActivityLog::with('user')
            ->whereHas('user', function ($query) use ($rt) {
                $query->where('role', 'admin')
                    ->where('rt_id', $rt->id);
            })
            ->latest()
            ->paginate(10);

        return view('admin.aktivitas.index', compact('activityLogs', 'nama_RT'));
    }
}
