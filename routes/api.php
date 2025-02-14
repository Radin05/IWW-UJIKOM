<?php

use App\Http\Controllers\Api\Keluarga;
use App\Http\Controllers\Api\Pembayaran;
use Illuminate\Http\Request;
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


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::resource('keluarga', Keluarga::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('pembayaran', Pembayaran::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('all_pembayaran', [\App\Http\Controllers\Api\Pembayaran::class, 'index2']);
});
