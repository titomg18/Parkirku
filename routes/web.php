<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\ParkingController;

// ================== ROOT ==================
Route::get('/', function () {
    return redirect()->route('login');
});

// ================== AUTH ==================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ================== ADMIN ==================
Route::get('/admin/dashboard', function () {
    if (auth()->user()->role != 'admin') {
        abort(403);
    }
    return view('admin.dashboard');
})->middleware('auth');

// ================== ADMIN - USER MANAGEMENT ==================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ================== PETUGAS ==================
Route::middleware('auth')->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [ParkingController::class, 'index'])->name('dashboard');
    Route::get('/masuk', [ParkingController::class, 'masukIndex'])->name('masuk.index');
    Route::post('/masuk', [ParkingController::class, 'masuk'])->name('masuk');
    Route::get('/karcis/{ticketCode}', [ParkingController::class, 'karcis'])->name('karcis');
    Route::get('/keluar', [ParkingController::class, 'keluarIndex'])->name('keluar');
    Route::post('/keluar', [ParkingController::class, 'keluarProses'])->name('keluar.proses');
});