<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UMKMController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/umkm', [UMKMController::class, 'index']);
Route::get('/umkm/{id}', [UMKMController::class, 'detail']);
