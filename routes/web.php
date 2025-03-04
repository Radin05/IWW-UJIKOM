<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AktivitasAdmin;
use App\Http\Controllers\admin\KasRTController;
use App\Http\Controllers\admin\KegiatanRtController;
use App\Http\Controllers\admin\KeluargaController;
use App\Http\Controllers\admin\PembayaranController;
use App\Http\Controllers\operator\Aktivitas;
use App\Http\Controllers\operator\ManajemenAdmin;
use App\Http\Controllers\operator\ManajemenSuperAdmin;
use App\Http\Controllers\operator\OperatorController;
use App\Http\Controllers\operator\RtController;
use App\Http\Controllers\superadmin\AktivitasSuperadmin;
use App\Http\Controllers\superadmin\KasRwController;
use App\Http\Controllers\superadmin\KegiatanRwController;
use App\Http\Controllers\Superadmin\KomentarController;
use App\Http\Controllers\superadmin\superadminController;
use App\Http\Controllers\user\ViewController;
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

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::resource('dashboard', OperatorController::class)->except(['show']);
    Route::resource('rt', RtController::class)->except(['show']);
    Route::resource('manajemen-superadmin', ManajemenSuperAdmin::class)->except(['show']);
    Route::resource('manajemen-admin', ManajemenAdmin::class)->except(['show']);
    Route::get('/aktivitas', [Aktivitas::class, 'index'])->name('aktivitas');
});

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [superadminController::class, 'index'])->name('index');
    Route::get('/kas-rw', [KasRwController::class, 'index'])->name('kas.index');
    Route::post('/kas-rw/update', [KasRwController::class, 'update'])->name('kas.update');
    Route::post('/kas-rw/pengeluaran', [KasRwController::class, 'store'])->name('pengeluaran-kas-rw.store');
    Route::put('/kas-rw/pengeluaran/update/{id}', [KasRwController::class, 'updatePengeluaran'])->name('pengeluaran-kas-rw.update');
    Route::resource('/kegiatan-rw', KegiatanRwController::class)->except(['show']);
    Route::resource('/komentar', KomentarController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/superadmin/komentar/getByKegiatan', [KomentarController::class, 'getByKegiatan'])->name('superadmin.komentar.getByKegiatan');
    Route::get('/aktivitas', [AktivitasSuperadmin::class, 'index'])->name('aktivitas');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/{nama_RT}/dashboard', [AdminController::class, 'index'])->name('index');
    Route::resource('/{nama_RT}/warga', KeluargaController::class)->except(['show']);
    Route::post('/{nama_RT}/warga/{keluarga}/store-akun', [KeluargaController::class, 'storeAkun'])->name('warga.storeAkun');
    Route::put('/{nama_RT}/warga/{no_kk_keluarga}/update-akun', [KeluargaController::class, 'updateAkun'])->name('warga.updateAkun');
    Route::resource('/{nama_RT}/pembayaran', PembayaranController::class)->except(['show'])
        ->parameters(['pembayaran' => 'pembayaran']);
    Route::resource('/{nama_RT}/kegiatan', KegiatanRtController::class)->except(['show']);
    Route::get('/{nama_RT}/kas-rt', [KasRTController::class, 'index'])->name('kas.index');
    Route::post('/{nama_RT}/kas-rt/update', [KasRTController::class, 'update'])->name('kas.update');
    Route::post('/{nama_RT}/kas-rt/pengeluaran', [KasRTController::class, 'store'])->name('pengeluaran.store');
    Route::put('/{nama_RT}/kas-rt/pengeluaran/update/{id}', [KasRTController::class, 'updatePengeluaran'])->name('pengeluaran-kas-rt.update');
    Route::post('/{nama_RT}/kas-rt/update-tahunan', [KasRTController::class, 'dataPerTahun'])->name('kas.update-tahunan');
    Route::get('/{nama_RT}/aktivitas', [AktivitasAdmin::class, 'index'])->name('aktivitas');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [ViewController::class, 'index'])->name('index');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
