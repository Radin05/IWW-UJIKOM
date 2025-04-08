<?php
namespace App\Http\Controllers\operator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class Aktivitas extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::with('user')
            ->latest()
            ->paginate(10);

        return view('operator.aktivitas.index', compact('activityLogs'));
    }

}
