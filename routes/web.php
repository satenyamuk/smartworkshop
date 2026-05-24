<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;
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

    // Peserta (Student & Teacher)
    Route::middleware('role:student,teacher')->group(function () {
        Route::get('/workshops/{workshop}/order', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/workshops/{workshop}/order', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    });

    // Panitia (Instructor only)
    Route::middleware('role:instructor')->prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/', [InstructorController::class, 'dashboard'])->name('dashboard');
        Route::get('/workshops/{workshop}/participants', [InstructorController::class, 'participants'])->name('workshops.participants');
        Route::post('/workshops/{workshop}/capacity', [InstructorController::class, 'updateCapacity'])->name('workshops.capacity');
        Route::post('/tickets/{ticket}/cancel', [InstructorController::class, 'cancelTicket'])->name('tickets.cancel');
    });

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/instructors', [AdminController::class, 'instructors'])->name('instructors');
        Route::post('/instructors/{user}/approve', [AdminController::class, 'approveInstructor'])->name('instructors.approve');
        
        // CRUD Kategori
        Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
        Route::get('/categories/create', [AdminController::class, 'categoriesCreate'])->name('categories.create');
        Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'categoriesEdit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'categoriesUpdate'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'categoriesDestroy'])->name('categories.destroy');
        
        // CRUD Kelas
        Route::get('/classes', [AdminController::class, 'classesIndex'])->name('classes.index');
        Route::get('/classes/create', [AdminController::class, 'classesCreate'])->name('classes.create');
        Route::post('/classes', [AdminController::class, 'classesStore'])->name('classes.store');
        Route::get('/classes/{class}/edit', [AdminController::class, 'classesEdit'])->name('classes.edit');
        Route::put('/classes/{class}', [AdminController::class, 'classesUpdate'])->name('classes.update');
        Route::delete('/classes/{class}', [AdminController::class, 'classesDestroy'])->name('classes.destroy');

        // CRUD Workshop
        Route::get('/workshops', [AdminController::class, 'workshopsIndex'])->name('workshops.index');
        Route::get('/workshops/create', [AdminController::class, 'workshopsCreate'])->name('workshops.create');
        Route::post('/workshops', [AdminController::class, 'workshopsStore'])->name('workshops.store');
        Route::get('/workshops/{workshop}/edit', [AdminController::class, 'workshopsEdit'])->name('workshops.edit');
        Route::put('/workshops/{workshop}', [AdminController::class, 'workshopsUpdate'])->name('workshops.update');
        Route::delete('/workshops/{workshop}', [AdminController::class, 'workshopsDestroy'])->name('workshops.destroy');
    });
});