<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Booking;
use App\Livewire\GuestBook;
use App\Livewire\GuestPage;
use App\Livewire\MyBookings;
use App\Livewire\ReportLostItems;
use App\Livewire\ValidationPage;
use App\Livewire\ViewBooking;
use App\Livewire\ViewResort;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/index');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Route::get('/app/booking', Booking::class)->middleware('auth');

// Route::get('/index', GuestPage::class)->name('index')->middleware('auth');

// Route::get('/guest-booking', GuestBook::class)->name('guest-booking')->middleware('auth');

// Route::get('view-resort/{id}', ViewResort::class)->name('view-resort')->middleware('auth');

// Route::get('validate', ValidationPage::class)->name('validate')->middleware('auth');

// Route::get('my-bookings', MyBookings::class)->name('my-bookings')->middleware('auth');

// Route::get('view-booking/{id}', ViewBooking::class)->name('view-booking')->middleware('auth');

// Route::get('lost-items', ReportLostItems::class)->name('lost-items')->middleware('auth');

Route::get('/index', GuestPage::class)->name('index');

Route::get('/guest-booking', GuestBook::class)->name('guest-booking');

Route::middleware('auth')->group(function () {
    Route::get('/app/booking', Booking::class);

    // Route::get('/index', GuestPage::class)->name('index');

    // Route::get('/guest-booking', GuestBook::class)->name('guest-booking');

    Route::get('view-resort/{id}', ViewResort::class)->name('view-resort');

    Route::get('validate', ValidationPage::class)->name('validate');

    Route::get('my-bookings', MyBookings::class)->name('my-bookings');

    Route::get('view-booking/{id}', ViewBooking::class)->name('view-booking');

    Route::get('lost-items', ReportLostItems::class)->name('lost-items');
});
