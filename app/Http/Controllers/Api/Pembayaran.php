<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran as ModelPembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Pembayaran extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->query('year', Carbon::now('Asia/Jakarta')->year);
        $month = $request->query('month', Carbon::now('Asia/Jakarta')->month);

        $pembayarans = ModelPembayaran::whereYear('tgl_pembayaran', $year)
            ->whereMonth('tgl_pembayaran', $month)
            ->get();

        return response()->json([
            'success'     => true,
            'message'     => 'Data pembayaran berhasil diambil.',
            'pembayarans' => $pembayarans,
        ], 200);
    }

    public function index2()
    {
        $pembayarans = ModelPembayaran::latest()->get();

        return response()->json([
            'success'     => true,
            'message'     => 'Data pembayaran berhasil diambil.',
            'pembayarans' => $pembayarans,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_kk_keluarga' => 'required|exists:keluargas,no_kk',
            'sejumlah'       => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
            'year'           => 'required|integer',
            'month'          => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $pembayaran = ModelPembayaran::create($request->all());

        return response()->json([
            'success'    => true,
            'message'    => 'Pembayaran berhasil disimpan melalui API.',
            'pembayaran' => $pembayaran,
        ], 201);
    }

}
