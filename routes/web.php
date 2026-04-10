<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeTicketController;
use App\Http\Controllers\WaitingListController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\TypeTicket;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TicketController;

// DEFAULT ROUTE
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/login-admin', function () {
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

    // EVENTS
    Route::resource('events', App\Http\Controllers\EventController::class);

    // SCHEDULES
    Route::resource('schedules', App\Http\Controllers\ScheduleController::class);

    // CATEGORIES
    Route::resource('categories', App\Http\Controllers\CategoryController::class);

    // LOCATIONS
    Route::resource('locations', App\Http\Controllers\LocationController::class);

    // TICKET TYPES
    Route::resource('ticket-types', App\Http\Controllers\TypeTicketController::class);
    Route::get('/ticket-types/event/{id}', [TypeTicketController::class, 'byEvent'])->name('ticket-types.manage');

    // RUTE WAITING LIST
    // 1. Rute untuk Admin melihat daftar antrean
    Route::get('/admin/waiting-list', [WaitingListController::class, 'index'])->name('admin.waiting-list.index');
    // 2. Rute untuk Admin mengubah status antrean
    Route::put('/admin/waiting-list/{waitingList}', [WaitingListController::class, 'update'])->name('waiting-list.update');
    // 3. Rute untuk user mendaftar ke Waiting List (Dari halaman checkout)
    Route::post('/waiting-list/join', [WaitingListController::class, 'join'])->name('waiting-list.join');
    // 4. Rute untuk user merespon notif (Terima/Tolak kuota)
    Route::post('/waiting-list/respond/{id}', [WaitingListController::class, 'respond'])->name('waiting-list.respond');

    // Rute fallback untuk store lama (opsional jika masih dipakai)
    Route::post('/waiting-list', [WaitingListController::class, 'store'])->name('waiting-list.store');
});
// -----------------------

// ----- USER ROUTES -----
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

Route::get('/checkout/payment', [TransactionController::class, 'payment'])->name('checkout.payment');
Route::post('/checkout/payment/process', [TransactionController::class, 'processPayment'])->name('checkout.payment.process');

Route::get('/e-ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/scan/{qr_code}', [TicketController::class, 'scanTicket']);

Route::get('/user-profile', function () {
    $userId = auth()->id() ?? 1;
    // Mengambil semua transaksi beserta data tiket dan event-nya, diurutkan dari yang terbaru
    $transactions = \App\Models\Transaction::with(['tickets.typeTicket.event'])
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('user.profile', compact('transactions'));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exports
    Route::get('/export/excel', [App\Http\Controllers\ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [App\Http\Controllers\ExportController::class, 'exportPdf'])->name('export.pdf');
});

require __DIR__.'/auth.php';
