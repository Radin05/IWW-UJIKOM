<?php
namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class Aktivitas extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::with('user')->latest()->get();

        return view('superadmin.aktivitas.index', compact('activityLogs'));
    }

}
