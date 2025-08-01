<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\LoginController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [UMKMController::class, 'adminDashboard']);

    Route::get('/admin/umkm/tambah', [UMKMController::class, 'create']);
    Route::post('/admin/umkm/tambah', [UMKMController::class, 'store']);
    Route::get('/admin/umkm/edit/{id}', [UmkmController::class, 'edit']);
    Route::post('/admin/umkm/update/{id}', [UmkmController::class, 'update']);
    Route::delete('/admin/umkm/delete/{id}', [UMKMController::class, 'destroy'])->name('umkm.destroy');
});

Route::get('/', [UMKMController::class, 'index']);
Route::get('/{id}', [UMKMController::class, 'detail']);
