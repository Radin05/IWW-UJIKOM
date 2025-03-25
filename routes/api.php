<?php

use App\Http\Controllers\Api\KasRt;
use App\Http\Controllers\Api\Keluarga;
use App\Http\Controllers\Api\Pembayaran;
use App\Http\Controllers\Api\KasRw;
use App\Http\Controllers\Api\Profil;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

    Route::get('/profil-user', [Profil::class, 'index']);

});

Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {

    Route::get('/profil-superadmin', [Profil::class, 'index']);

    Route::get('/kas-rw', [KasRw::class, 'index']);
    Route::put('/kas-rw/{id}', [KasRw::class, 'update']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::get('/profil', [Profil::class, 'index']);

    Route::get('/keluarga', [Keluarga::class, 'index']);
    Route::post('/keluarga', [Keluarga::class, 'store']);
    // Route::put('/keluarga/{id}', [Keluarga::class, 'update']);
    // Route::delete('/keluarga/{id}', [Keluarga::class, 'destroy']);

    Route::get('/pembayaran', [Pembayaran::class, 'index']);
    Route::post('/pembayaran', [Pembayaran::class, 'store']);
    // Route::put('/pembayaran/{id}', [Pembayaran::class, 'update']);
    // Route::delete('/pembayaran/{id}', [Pembayaran::class, 'destroy']);

    Route::get('all_pembayaran', [\App\Http\Controllers\Api\Pembayaran::class, 'index2']);
    Route::get('/kas-rt/{nama_RT}', [KasRt::class, 'index']);
    Route::put('/kas-rt/{nama_RT}', [KasRt::class, 'update']);
});

