<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
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



Route::middleware('guest:karyawan')->group(function () {
    // Auth
    Route::get('/', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/prosesLogin', [AuthController::class, 'prosesLogin'])->name('auth.proses');
});

Route::middleware(['auth:karyawan'])->group(function () {
    // Auth
    Route::get('/prosesLogout', [AuthController::class, 'prosesLogout'])->name('auth.logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');
});
