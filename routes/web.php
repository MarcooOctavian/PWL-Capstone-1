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
use App\Http\Middleware\CheckUserStatus;
use App\Http\Controllers\EventController;

// DEFAULT ROUTE
Route::get('/', function () {
    $events = Event::where(function ($query) {
            $query->where('status', 'Upcoming')
                ->orWhere('status', 'upcoming');
        })
        ->whereDate('date', '>=', \Carbon\Carbon::today())
        ->latest('date')
        ->get();

    $nextEvent = Event::where(function ($query) {
            $query->where('status', 'Upcoming')
                ->orWhere('status', 'upcoming');
        })
        ->whereDate('date', '>=', \Carbon\Carbon::today())
        ->orderBy('date', 'asc')
        ->first();

    $countdownTarget = $nextEvent
        ? \Carbon\Carbon::parse($nextEvent->date)->format('Y/m/d')
        : null;

    return view('user.index', compact('events', 'nextEvent', 'countdownTarget'));
})->name('home');
// -----------------------

// ADMIN LOGIN ROUTE
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

//REACTIVATE ACCOUNT
Route::get('/reactivate', function () {
    return view('auth.reactivate');
})->middleware('auth');

Route::get('/reactivate-user', function () {
    return view('auth.reactivate-user');
})->middleware('auth');

Route::post('/reactivate', [UserController::class, 'reactivate'])
    ->name('reactivate.process')
    ->middleware('auth');
// -----------------------

// ADMIN PANEL (ROLE 1 & 2)
Route::middleware(['auth', CheckUserStatus::class,RoleMiddleware::class])->group(function () {
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
    // ADMIN ONLY ROUTES
    Route::middleware(RoleMiddleware::class.':1')->group(function () {
        // USERS
        Route::resource('users', UserController::class);
        Route::patch('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        
        // CATEGORIES
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        
        // LOCATIONS
        Route::resource('locations', App\Http\Controllers\LocationController::class);
    });

    // EVENTS
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('events', EventController::class);
    });
    
    // SCHEDULES
    Route::resource('schedules', App\Http\Controllers\ScheduleController::class);
    
    // TICKET TYPES
    Route::resource('ticket-types', App\Http\Controllers\TypeTicketController::class);
    Route::get('/ticket-types/event/{id}', [TypeTicketController::class, 'byEvent'])->name('ticket-types.manage');

    // RUTE WAITING LIST
    // 1. Rute untuk Admin melihat daftar antrean
    Route::get('/admin/waiting-list', [WaitingListController::class, 'index'])->name('admin.waiting-list.index');
    // 2. Rute untuk Admin mengubah status antrean
    Route::put('/admin/waiting-list/{waitingList}', [WaitingListController::class, 'update'])->name('waiting-list.update');
    Route::put('/admin/waiting-list/{waitingList}', [WaitingListController::class, 'update'])->name('waiting-list.update');
});
// -----------------------

// ----- USER ROUTES -----
Route::middleware(['auth', CheckUserStatus::class])->group(function () {
    Route::get('/home', function () {
        return redirect()->route('home');
    });

    Route::get('/user-profile', function () {
        $transactions = \App\Models\Transaction::with(['tickets.typeTicket.event'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.profile', compact('transactions'));
    });

    // 3. Rute untuk user mendaftar ke Waiting List (Dari halaman checkout)
    Route::post('/waiting-list/join', [WaitingListController::class, 'join'])->name('waiting-list.join');
    // 4. Rute untuk user merespon notif (Terima/Tolak kuota)
    Route::post('/waiting-list/respond/{id}', [WaitingListController::class, 'respond'])->name('waiting-list.respond');
    // Rute fallback untuk store lama (opsional jika masih dipakai)
    Route::post('/waiting-list', [WaitingListController::class, 'store'])->name('waiting-list.store');

    Route::get('/e-ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
});

// CHECKOUT
Route::middleware(['auth', CheckUserStatus::class])->group(function () {
    Route::get('/checkout', [TransactionController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [TransactionController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment', [TransactionController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/process', [TransactionController::class, 'processPayment'])->name('checkout.payment.process');

});

Route::get('/scan/{qr_code}', [TicketController::class, 'scanTicket']);

Route::middleware(['auth', CheckUserStatus::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exports
    Route::get('/export/excel', [App\Http\Controllers\ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [App\Http\Controllers\ExportController::class, 'exportPdf'])->name('export.pdf');
});

// GUEST USER
Route::middleware('guest')->group(function () {
    Route::get('/user-login', function () {
        return view('user.login');
    });

    Route::get('/user-register', function () {
        return view('user.register');
    });
});

// PUBLIC
Route::get('/events', [EventController::class, 'publicIndex'])->name('events.public');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show.public');

require __DIR__.'/auth.php';

Route::get('/login', function () {
    return view('admin.login');
})->name('login');
