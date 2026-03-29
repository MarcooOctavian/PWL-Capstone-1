<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
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

Route::get('/home', function () {
    return view('user.index');
})->name('user.home');

Route::get('/schedule', function () {
    return view('user.schedule');
});

Route::get('/speaker', function () {
    return view('user.speaker');
});

Route::get('/panel', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('events', App\Http\Controllers\EventController::class)->middleware(['auth']);

Route::resource('schedules', App\Http\Controllers\ScheduleController::class)->middleware(['auth']);

Route::resource('categories', App\Http\Controllers\CategoryController::class)->middleware(['auth']);

Route::resource('locations', App\Http\Controllers\LocationController::class)->middleware(['auth']);


require __DIR__.'/auth.php';
