<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\KasRw;
use App\Models\KegiatanRw;
use App\Models\PengeluaranKasRw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class superadminController extends Controller
{
    public function index()
    {
        $kasRw        = KasRw::first();
        $pengeluaran  = PengeluaranKasRw::sum('nominal');
        $kegiatan     = KegiatanRw::orderBy('tanggal_kegiatan', 'desc')->get();
        $carbon = Carbon::now('Asia/Jakarta');

        return view('superadmin.dashboard', compact('kasRw', 'pengeluaran', 'kegiatan', 'carbon'));
    }

}
