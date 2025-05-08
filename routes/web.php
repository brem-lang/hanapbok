<?php

use App\Http\Controllers\ResortController;
use App\Http\Controllers\SocialiteController;
use App\Livewire\Booking;
use App\Livewire\GuestBook;
use App\Livewire\GuestPage;
use App\Livewire\ViewResort;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/app');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

Route::get('/app/booking', Booking::class)->middleware('auth');

Route::get('/index', GuestPage::class)->name('index')->middleware('auth');

Route::get('/guest-booking', GuestBook::class)->name('guest-booking')->middleware('auth');

Route::get('view-resort/{id}', ViewResort::class)->name('view-resort')->middleware('auth');

// Route::get('resorts', [ResortController::class, 'getResorts'])->name('resorts')->middleware('auth');
