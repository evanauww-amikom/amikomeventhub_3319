<?php

use Illuminate\Support\Facades\Route;

// USER CONTROLLERS
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\OrganizerEventController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TicketController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AdminOrganizerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC / USER ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/event-detail/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/ticket/{id}', [EventController::class, 'ticket'])->name('ticket');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ==================== GOOGLE SSO (untuk user biasa) ====================
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('auth.google.callback');

Route::get('/masuk', function () {
    return view('auth.user-login');
})->name('user.login');

// Pembayaran & Sukses Midtrans
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans/callback', [CheckoutController::class, 'notification'])->name('checkout.notification');

// ==================== ORGANIZER: PUBLIC (belum login) ====================
Route::get('/daftar-mitra', [OrganizerController::class, 'showRegister'])->name('organizer.register.form');
Route::post('/daftar-mitra', [OrganizerController::class, 'register'])->name('organizer.register');

// ==================== ORGANIZER: LOGGED IN (belum tentu approved) ====================
Route::middleware('auth')->group(function () {
    Route::get('/organizer/pending', [OrganizerController::class, 'pending'])->name('organizer.pending');
});

// ==================== ORGANIZER: APPROVED ONLY (MULTI-TENANT) ====================
Route::prefix('organizer')->name('organizer.')->middleware(['auth', 'organizer'])->group(function () {
    Route::get('dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::resource('events', OrganizerEventController::class)->except(['show']);

    // --- FITUR PILIHAN: QR CODE CHECK-IN SCANNER ---
    Route::get('scanner', [OrganizerEventController::class, 'scanner'])->name('scanner');
    Route::post('scanner/verify', [OrganizerEventController::class, 'verifyQr'])->name('scanner.verify');
});

// ==================== ADMIN ROUTES ====================
Route::redirect('/admin', '/admin/dashboard');

Route::prefix('admin')->name('admin.')->group(function () {

    // --- Guest / Public Admin Routes (Auth) ---
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // --- Protected Admin Routes (superadmin only) ---
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Resource Routes
        Route::resource('events', AdminEventController::class);
        Route::resource('categories', CategoriesController::class)->except(['create', 'show', 'edit']);
        Route::resource('partners', AdminPartnerController::class)->except(['create', 'show', 'edit']);

        // Transaction Routes
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Kelola Organizer (approve/reject)
        Route::get('organizers', [AdminOrganizerController::class, 'index'])->name('organizers.index');
        Route::post('organizers/{organizer}/approve', [AdminOrganizerController::class, 'approve'])->name('organizers.approve');
        Route::post('organizers/{organizer}/reject', [AdminOrganizerController::class, 'reject'])->name('organizers.reject');

        // Dashboard Analytics & Charts
        Route::get('dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');
    });
});

// ==================== Halaman "Tiket Saya" & Review (harus login) ====================
Route::middleware('auth')->group(function () {
    Route::get('/tiket-saya', [TicketController::class, 'index'])->name('tickets.mine');
    Route::post('/events/{event}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// ==================== Profil publik organizer ====================
Route::get('/penyelenggara/{organizer:slug}', [OrganizerController::class, 'publicProfile'])->name('organizer.profile');