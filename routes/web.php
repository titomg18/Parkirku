<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// ================== AUTH ==================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ================== ADMIN ==================
Route::get('/admin/dashboard', function () {
    if (auth()->user()->role != 'admin') {
        abort(403);
    }

    return view('admin.dashboard'); // 👉 sesuai folder admin
})->middleware('auth');

// ================== PETUGAS ==================
Route::get('/petugas/dashboard', function () {
    if (auth()->user()->role != 'petugas') {
        abort(403);
    }

    return view('petugas.dashboard'); // 👉 sesuai folder petugas
})->middleware('auth');