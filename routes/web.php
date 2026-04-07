<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeTicketController;
use App\Http\Controllers\WaitingListController;

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
Route::post('/admin/forgot-password', [RegisteredUserController::class, 'forgotPassword'])
    ->name('admin.forgot-password.post');

Route::get('/admin/recover-password', function () {
    return view('admin.recover-password');
})->name('admin.recover-password');

Route::post('/admin/reset-password', [RegisteredUserController::class, 'resetPassword'])
    ->name('admin.reset-password');

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

Route::get('/ticket-types', [TypeTicketController::class, 'index'])
    ->name('ticket-types.index');

Route::get('/ticket-types/event/{id}', [TypeTicketController::class, 'byEvent'])
    ->name('ticket-types.manage');

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
// Rute testing UI sementara (hardcode)
Route::get('/e-ticket/{id}', function ($id) {
    // Membuat objek tiket palsu (dummy)
    $ticket = new stdClass();
    $ticket->qr_code = 'DUMMY-QR-CODE-12345';

    // Mencegah error pada relasi objek di e-ticket.blade.php
    $ticket->typeTicket = new stdClass();
    $ticket->typeTicket->name = 'VIP';
    $ticket->typeTicket->event = new stdClass();
    $ticket->typeTicket->event->title = 'Maranatha Tech Conference 2026';
    $ticket->typeTicket->event->date = '2026-05-29';

    $ticket->transaction = new stdClass();
    $ticket->transaction->user = new stdClass();
    $ticket->transaction->user->name = 'Richard Vincentius';

    return view('user.e-ticket', compact('ticket'));
})->name('ticket.show');

// Rute testing UI untuk panitia memindai QR Code
Route::get('/scan/{qr_code}', function ($qr_code) {
    // Membuat data dummy
    $ticket = new stdClass();
    $ticket->qr_code = $qr_code;
    $ticket->transaction = new stdClass();
    $ticket->transaction->user = new stdClass();
    $ticket->transaction->user->name = 'Richard Vincentius';

    // Memanggil file scan-result.blade.php
    return view('user.scan-result', compact('ticket'));
});

Route::get('/user-profile', function () {
    return view('user.profile');
});

use App\Models\Transaction;
use App\Models\Ticket;

use App\Models\Event;
use App\Models\TypeTicket;

Route::get('/panel', function () {
    if (!auth()->check() || !in_array(auth()->user()->role, [1, 2])) {
        redirect('/home');
    }
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

// Waiting List
Route::middleware('auth')->group(function () {
    Route::post('/waiting-list', [WaitingListController::class, 'store'])->name('waiting-list.store');

    Route::get('/admin/waiting-list', [WaitingListController::class, 'index'])->name('admin.waiting-list.index');
    Route::patch('/admin/waiting-list/{waitingList}', [WaitingListController::class, 'update'])->name('admin.waiting-list.update');
});

Route::resource('events', App\Http\Controllers\EventController::class)->middleware(['auth']);

Route::resource('schedules', App\Http\Controllers\ScheduleController::class)->middleware(['auth']);

Route::resource('categories', App\Http\Controllers\CategoryController::class)->middleware(['auth']);

Route::resource('ticket-types', App\Http\Controllers\TypeTicketController::class)->middleware(['auth']);

Route::resource('locations', App\Http\Controllers\LocationController::class)->middleware(['auth']);


require __DIR__.'/auth.php';
