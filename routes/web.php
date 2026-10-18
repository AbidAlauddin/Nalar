<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MaterialCategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard utama untuk pengguna yang sudah login.
// Controller menentukan tampilan berdasarkan role resmi (super_admin, teacher, student).
Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

// Route katalog dan pembacaan materi pembelajaran SMA (untuk siswa & pengguna terautentikasi).
Route::middleware('auth')->group(function () {
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material:slug}', [MaterialController::class, 'show'])->name('materials.show');
});

// Route khusus Guru (teacher) untuk pengelolaan materi dan evaluasi pembelajaran.
// Kategori materi bersifat read-only bagi guru (dibuat & dikelola oleh Super Admin).
Route::middleware(['auth', 'teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacher'])->name('dashboard');
        Route::get('/categories', [MaterialCategoryController::class, 'teacherIndex'])->name('categories.index');
        Route::get('/categories/{category}', [MaterialCategoryController::class, 'show'])->name('categories.show');
        Route::resource('materials', MaterialManagementController::class);
    });

// Route khusus Super Admin untuk modul pengelolaan platform penuh.
Route::middleware(['auth', 'super.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('categories', MaterialCategoryController::class);
        Route::resource('materials', MaterialManagementController::class);
    });
