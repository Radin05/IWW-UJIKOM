<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class AktivitasSuperadmin extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'superadmin');
            })
            ->latest()
            ->paginate(10);

        return view('superadmin.aktivitas.index', compact('activityLogs'));
    }

}
