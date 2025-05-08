<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (Auth::check()) {
            if (auth()->user()->isGuest()) {
                return redirect()->route('index');
            }
        }

        return parent::toResponse($request);
    }
}
