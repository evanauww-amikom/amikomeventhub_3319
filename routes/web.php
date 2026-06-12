<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\TransactionController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('homepage');

Route::get('/welcome', function() {
    return view('welcome');
});

Route::get('/kontak', function() {
    return view('contact');
});

Route::get('/profil', function() {
    return view('profil');
});

Route::get('/katalog', function() {
    return view('katalog');
});

Route::get('/bantuan', function() {
    return view('bantuan');
});

Route::get('/event-detail', function() {
    return view('event-detail');
});

Route::get('/checkout', function() {
    return view('checkout');
});

Route::get('/ticket', function() {
    return view('ticket');
});

Route::get('/dashboard', function() {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/event', function() {
    return view('admin.events');
})->name('admin.event');

Route::get('/transaction', function() {
    return view('admin.transaction');
})->name('admin.transaction');

Route::get('/admin/categories/index', function() {
    return view('admin.categories.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', EventAdminController::class);
    Route::resource('categories', \App\Http\Controllers\CategoriesController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', EventAdminController::class);
    Route::resource('categories', \App\Http\Controllers\CategoriesController::class);
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Grouping untuk URL berawalan /admin
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Login bebas akses
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Route Administrasi di balik tembok (Middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('events', EventController::class);
        
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
    });

});