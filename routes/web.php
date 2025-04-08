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
use Illuminate\Support\Facades\Http;
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

// Route::get('/register', function () {
//     return redirect('/login');
// });

Route::middleware(['auth', 'role:operator', 'limit.operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::resource('dashboard', OperatorController::class)->except(['show']);
    Route::resource('rt', RtController::class)->except(['show']);
    Route::resource('manajemen-superadmin', ManajemenSuperAdmin::class)->except(['show']);
    Route::put('/manajemen-superadmin/{id}/update-password', [ManajemenSuperAdmin::class, 'updatePassword'])
        ->name('manajemen-superadmin.update-password');
    Route::put('/manajemen-admin/{id}/update-password', [ManajemenAdmin::class, 'updatePassword'])
        ->name('manajemen-admin.update-password');
    Route::resource('manajemen-admin', ManajemenAdmin::class)->except(['show']);
    Route::get('/aktivitas', [Aktivitas::class, 'index'])->name('aktivitas');
});

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [superadminController::class, 'index'])->name('index');
    Route::get('/kas-rw', [KasRwController::class, 'index'])->name('kas.index');
    Route::post('/kas-rw/update', [KasRwController::class, 'update'])->name('kas.update');
    Route::post('/kas-rw/uang-tambahan-kas', [KasRwController::class, 'storeUangTambahan'])->name('uang-tambahan-kas.store');
    Route::put('/kas-rw/uang-tambahan-kas/{id}', [KasRwController::class, 'updateUangTambahan'])->name('uang-tambahan-kas.update');
    Route::delete('/kas-rw/uang-tambahan-kas/{id}', [KasRwController::class, 'destroyUangTambahan'])->name('uang-tambahan-kas.destroy');
    Route::post('/kas-rw/pengeluaran', [KasRwController::class, 'store'])->name('pengeluaran-kas-rw.store');
    Route::put('/kas-rw/pengeluaran/update/{id}', [KasRwController::class, 'updatePengeluaran'])->name('pengeluaran-kas-rw.update');
    Route::delete('/kas-rw/pengeluaran/{id}', [KasRwController::class, 'destroyPengeluaran'])->name('pengeluaran-kas-rw.destroy');
    Route::resource('/kegiatan-rw', KegiatanRwController::class)->except(['show']);
    Route::get('/aktivitas', [AktivitasSuperadmin::class, 'index'])->name('aktivitas');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/{nama_RT}/dashboard', [AdminController::class, 'index'])->name('index');
    Route::resource('/{nama_RT}/warga', KeluargaController::class)->except(['show']);
    Route::put('/{nama_RT}/warga/{warga}', [KeluargaController::class, 'updateKeluarga'])->name('warga.update');
    Route::post('/{nama_RT}/warga/{keluarga}/store-akun', [KeluargaController::class, 'storeAkun'])->name('warga.storeAkun');
    Route::put('/{nama_RT}/warga/{no_kk_keluarga}/update-akun', [KeluargaController::class, 'updateAkun'])->name('warga.updateAkun');
    Route::resource('/{nama_RT}/pembayaran', PembayaranController::class)->except(['show'])
        ->parameters(['pembayaran' => 'pembayaran']);
    Route::resource('/{nama_RT}/kegiatan', KegiatanRtController::class)->except(['show']);
    Route::get('/{nama_RT}/kas-rt', [KasRTController::class, 'index'])->name('kas.index');
    Route::post('/{nama_RT}/kas-rt/update', [KasRTController::class, 'update'])->name('kas.update');
    Route::post('/{nama_RT}/kas-rw/uang-tambahan-kas', [KasRTController::class, 'storeUangTambahan'])->name('uang-tambahan-kas.store');
    Route::put('{nama_RT}/kas-rt/uang-tambahan-kas/{id}', [KasRTController::class, 'updateUangTambahan'])->name('uang-tambahan-kas.update');
    Route::delete('{nama_RT}/kas-rt/uang-tambahan-kas/{id}', [KasRTController::class, 'destroyUangTambahan'])->name('uang-tambahan-kas.destroy');

    Route::post('/{nama_RT}/kas-rt/pengeluaran', [KasRTController::class, 'store'])->name('pengeluaran.store');
    Route::put('/{nama_RT}/kas-rt/pengeluaran/update/{id}', [KasRTController::class, 'updatePengeluaran'])->name('pengeluaran-kas-rt.update');
    Route::delete('{nama_RT}/kas-rt/pengeluaran/{id}', [KasRTController::class, 'destroyPengeluaranRt'])->name('pengeluaran-kas-rt.destroy');
    Route::get('/{nama_RT}/aktivitas', [AktivitasAdmin::class, 'index'])->name('aktivitas');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('warga.')->group(function () {
    Route::get('/dashboard', [ViewController::class, 'index'])->name('index');
    Route::get('/dashboard/iuran', [ViewController::class, 'iuran'])->name('iuran');
    Route::get('/dashboard/profil', [ViewController::class, 'profil'])->name('profil');
    Route::put('/dashboard/profil/update', [ViewController::class, 'updateProfile'])->name('updateProfile');
    Route::put('/dashboard/profil/update-password', [ViewController::class, 'updatePassword'])->name('updatePassword');
});

Route::get('/halaman-404', function () {
    return view('error.404');
})->name('halaman.404');

Route::get('/halaman-403', function () {
    return view('error.403');
})->name('halaman.403');


Auth::routes(['register' => false]);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
