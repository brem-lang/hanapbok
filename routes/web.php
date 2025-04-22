<?php

use App\Http\Controllers\ResortController;
use App\Http\Controllers\SocialiteController;
use App\Livewire\Booking;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/app');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

Route::get('/app/booking', Booking::class)->middleware('auth');
// Route::get('resorts', [ResortController::class, 'getResorts'])->name('resorts')->middleware('auth');
