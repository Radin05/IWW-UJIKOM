<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluarga as ModelKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Keluarga extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();

        $keluargas = ModelKeluarga::where('rt_id', $admin->rt_id)
            ->orderByRaw('LENGTH(alamat), alamat ASC')
            ->get();

        return response()->json([
            'message'   => 'Data keluarga berhasil diambil.',
            'keluargas' => $keluargas,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_kk'         => 'required|unique:keluargas,no_kk',
            'nama_keluarga' => 'required|string|max:255',
            'alamat'        => 'required|string|max:255',
            'no_telp'       => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $keluargas = ModelKeluarga::create($request->all());

        return response()->json([
            'success'    => true,
            'message'    => 'Pembayaran berhasil disimpan melalui API.',
            'pembayaran' => $keluargas,
        ], 201);
    }

}
