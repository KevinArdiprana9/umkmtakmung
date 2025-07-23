<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [UMKMController::class, 'adminDashboard']);

    Route::get('/admin/umkm/tambah', [UMKMController::class, 'create']);
    Route::post('/admin/umkm/tambah', [UMKMController::class, 'store']);
});

Route::get('/umkm', [UMKMController::class, 'index']);
Route::get('/umkm/{id}', [UMKMController::class, 'detail']);
