<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/login-admin', function () {
    // Ini akan memanggil file resources/views/admin/login.blade.php
    return view('admin.login');
})->name('admin.login');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/admin/forgot-password', function () {
    return view('admin.forgot-password');
})->name('admin.forgot-password');

Route::get('/admin/recover-password', function () {
    return view('admin.recover-password');
})->name('admin.recover-password-password');

Route::resource('users', UserController::class);

Route::get('/home', function () {
    return view('user.index');
})->middleware(['auth'])->name('user.home');

Route::get('/schedule', function () {
    return view('user.schedule');
});

Route::get('/speaker', function () {
    return view('user.speaker');
});

Route::get('/event-detail', function () {
    return view('user.blog-details');
});

Route::middleware('guest')->group(function () {
    Route::get('/user-login', function () {
        return view('user.login');
    });

    Route::get('/user-register', function () {
        return view('user.register');
    });
});

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TicketController;

Route::get('/checkout', [TransactionController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [TransactionController::class, 'store'])->name('checkout.store');
Route::get('/e-ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');

Route::get('/user-profile', function () {
    return view('user.profile');
});

use App\Models\Transaction;
use App\Models\Ticket;

use App\Models\Event;
use App\Models\TypeTicket;

Route::get('/panel', function () {
    $metrics = [
        'events_count' => Event::count(),
        'types_count' => TypeTicket::count(),
        'tickets_count' => Ticket::count(),
        'revenue' => Transaction::sum('total_amount'),
    ];

    $latestTransactions = Transaction::with('user')->latest()->take(5)->get();
    $soldTickets = Ticket::with(['transaction.user', 'typeTicket.event'])->latest()->take(10)->get();

    return view('admin.dashboard', compact('latestTransactions', 'soldTickets', 'metrics'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exports
    Route::get('/export/excel', [App\Http\Controllers\ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [App\Http\Controllers\ExportController::class, 'exportPdf'])->name('export.pdf');
});

Route::resource('events', App\Http\Controllers\EventController::class)->middleware(['auth']);

Route::resource('schedules', App\Http\Controllers\ScheduleController::class)->middleware(['auth']);

Route::resource('categories', App\Http\Controllers\CategoryController::class)->middleware(['auth']);

Route::resource('ticket-types', App\Http\Controllers\TypeTicketController::class)->middleware(['auth']);

Route::resource('locations', App\Http\Controllers\LocationController::class)->middleware(['auth']);


require __DIR__.'/auth.php';
