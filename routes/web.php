<?php

use App\Http\Controllers\GuestReviewController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\SocialiteController;
use App\Livewire\Booking;
use App\Livewire\GuestBook;
use App\Livewire\GuestPage;
use App\Livewire\MyBookings;
use App\Livewire\ReportLostItems;
use App\Livewire\Review;
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

    Route::get('review/{id}', Review::class)->name('review');

    Route::get('validate', ValidationPage::class)->name('validate');

    Route::get('my-bookings', MyBookings::class)->name('my-bookings');

    Route::get('view-booking/{id}', ViewBooking::class)->name('view-booking');

    Route::get('lost-items', ReportLostItems::class)->name('lost-items');

    Route::get('/profile', \App\Livewire\ProFile::class)->name('profile');

    Route::get('/revenue-summary/print', [PrintController::class, 'printRevenueReport'])
        ->name('revenue.print');

    Route::get('/reports/print/{resort_id}', [GuestReviewController::class, 'export'])
        ->name('reports.print');

    Route::get('/lost-found/print/{resort_id}', [LostFoundController::class, 'export'])
        ->name('lostFound.print');
});
