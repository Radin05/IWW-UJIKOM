<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\KeluargaController;
use App\Http\Controllers\superadmin\ManajemenAdmin;
use App\Http\Controllers\superadmin\ManajemenSuperAdmin;
use App\Http\Controllers\superadmin\RtController;
use App\Http\Controllers\superadmin\Aktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    Route::resource('rt', RtController::class)->except(['show']);

    Route::resource('manajemen-superadmin', ManajemenSuperAdmin::class)->except(['show']);
    Route::resource('manajemen-admin', ManajemenAdmin::class)->except(['show']);

    Route::get('/aktivitas', [Aktivitas::class, 'index'])->name('aktivitas');

});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/{nama_RT}/dashboard', [AdminController::class, 'index'])->name('index');

    Route::resource('/{nama_RT}/warga', KeluargaController::class)->except(['show']);
    Route::post('/{nama_RT}/warga/{keluarga}/store-akun', [KeluargaController::class, 'storeAkun'])->name('warga.storeAkun');
    Route::put('/{nama_RT}/warga/{no_kk_keluarga}/update-akun', [KeluargaController::class, 'updateAkun'])->name('warga.updateAkun');


});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
