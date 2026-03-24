<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login-admin', function () {
    // Ini akan memanggil file resources/views/admin/login.blade.php
    return view('admin.login');
})->name('admin.login');

Route::get('/admin/register', function () {
    return view('admin.register');
})->name('admin.register');

Route::get('/admin/forgot-password', function () {
    return view('admin.forgot-password');
})->name('admin.forgot-password');

Route::get('/admin/recover-password', function () {
    return view('admin.recover-password');
})->name('admin.recover-password-password');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
