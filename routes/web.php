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

// DEFAULT ROUTE
Route::get('/', function () {
    return redirect()->route('admin.login');
});
// -----------------------

// ADMIN LOGIN ROUTE
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
// -----------------------

// ADMIN PANEL MIDDLEWARE (ROLE 1,2 BISA MASUK)
Route::middleware([RoleMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/panel', function () {
        $range = request('range', '7days');
        $startDate = \Carbon\Carbon::now();

        switch ($range) {
            case 'today': $startDate = \Carbon\Carbon::today(); break;
            case '30days': $startDate = \Carbon\Carbon::now()->subDays(30); break;
            case '1year': $startDate = \Carbon\Carbon::now()->subYear(); break;
            default: $startDate = \Carbon\Carbon::now()->subDays(7); break;
        }

        // 1. DATA: GRAFIK TRANSAKSI (Line Chart)
        $dailySales = Transaction::where('payment_status', 'paid')
            ->where('transaction_date', '>=', $startDate)
            ->selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total_revenue')
            ->groupBy('date')->orderBy('date', 'ASC')->get();
        $dates = $dailySales->pluck('date');
        $revenues = $dailySales->pluck('total_revenue');

        // 2. DATA: EVENT PERFORMANCE (Bar Chart - Tiket laku per event)
        $events = Event::withCount(['tickets' => function($q) use ($startDate) {
            $q->whereHas('transaction', function($t) use ($startDate) {
                $t->where('payment_status', 'paid')->where('transaction_date', '>=', $startDate);
            });
        }])->get();
        $eventLabels = $events->pluck('title');
        $eventData = $events->pluck('tickets_count');

        // 3. DATA: STATISTIK PENJUALAN (Doughnut Chart - Perbandingan Jenis Tiket)
        $ticketTypes = TypeTicket::withCount(['tickets' => function($q) use ($startDate) {
            $q->whereHas('transaction', function($t) use ($startDate) {
                $t->where('payment_status', 'paid')->where('transaction_date', '>=', $startDate);
            });
        }])->get();
        $typeLabels = $ticketTypes->pluck('name');
        $typeData = $ticketTypes->pluck('tickets_count');

        // METRICS KOTAK ATAS
        $metrics = [
            'events_count' => Event::count(),
            'types_count' => TypeTicket::count(),
            'tickets_count' => Ticket::whereHas('transaction', function($q) use ($startDate) {
                $q->where('transaction_date', '>=', $startDate)->where('payment_status', 'paid');
            })->count(),
            'revenue' => Transaction::where('payment_status', 'paid')
                ->where('transaction_date', '>=', $startDate)->sum('total_amount'),
        ];

        $latestTransactions = Transaction::with('user')->latest()->take(5)->get();
        $soldTickets = Ticket::with(['transaction.user', 'typeTicket.event'])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'latestTransactions', 'soldTickets', 'metrics',
            'dates', 'revenues', 'range',
            'eventLabels', 'eventData', 'typeLabels', 'typeData' // <- Data baru untuk grafik
        ));
    })->middleware(['auth', 'verified'])->name('dashboard');

    // USERS
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

    // WAITING LIST ADMIN
    Route::get('/admin/waiting-list', [WaitingListController::class, 'index']);
    Route::patch('/admin/waiting-list/{waitingList}', [WaitingListController::class, 'update']);

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
Route::get('/scan/{qr_code}', [TicketController::class, 'scanTicket']);

Route::get('/user-profile', function () {
    // Mengambil ID user yang login (atau default 1 untuk testing seperti di controller Anda)
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

// Waiting List
Route::middleware('auth')->group(function () {
    Route::post('/waiting-list', [WaitingListController::class, 'store'])->name('waiting-list.store');
});

Route::get('/checkout/payment', [\App\Http\Controllers\TransactionController::class, 'payment'])->name('checkout.payment');

Route::post('/checkout/payment/process', [\App\Http\Controllers\TransactionController::class, 'processPayment'])->name('checkout.payment.process');

require __DIR__.'/auth.php';
