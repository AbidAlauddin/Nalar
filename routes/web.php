
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard utama untuk pengguna yang sudah login.
// Controller menentukan tampilan berdasarkan role pengguna.
Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

// Route khusus Super Admin untuk modul pengelolaan.
Route::middleware(['auth', 'super.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Tambahkan route materi, kuis, pengguna, dan lainnya di sini.
    });
