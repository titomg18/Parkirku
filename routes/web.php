<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;

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
Route::get('/petugas/dashboard', function () {
    if (auth()->user()->role != 'petugas') {
        abort(403);
    }
    return view('petugas.dashboard');
})->middleware('auth');