<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

// --- Public Routes ---
Route::get('/', [WorkshopController::class, 'index'])->name('home');
Route::get('/workshops/{workshop}', [WorkshopController::class, 'show'])->name('workshops.show');

// --- Guest Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'show'])->name('login');
    Route::post('/login',    [LoginController::class,    'store']);
    Route::get('/register',  [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// --- Authenticated Routes ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Student & Teacher
    Route::middleware('role:student,teacher')->group(function () {
        // tickets, orders — coming next
    });

    // Instructor only
    Route::middleware('role:instructor')->prefix('instructor')->name('instructor.')->group(function () {
        // workshop management — coming next
    });

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // admin dashboard — coming next
    });
});