<?php

namespace App\Http\Responses;

use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    // public function toResponse($request): RedirectResponse|Redirector
    // {
    //     $user = auth()->user();
    //     if (Auth::check()) {

    //         if (
    //             $user->isResortsAdmin() &&
    //             ! $user->AdminResort?->id
    //         ) {
    //             auth()->logout();

    //             Notification::make()
    //                 ->title('No Resort Assigned')
    //                 ->icon('heroicon-o-exclamation-circle')
    //                 ->body('Please contact administrator.')
    //                 ->warning()
    //                 ->send();

    //             return redirect('app');
    //         }

    //         auth()->user()->generateCode();

    //         return redirect()->route('2fa.index');
    //     }

    //     return parent::toResponse($request);
    // }

    public function toResponse($request): RedirectResponse|Redirector
    {
        if (Auth::check()) {

            $user = auth()->user();

            // 1. If user is GUEST → redirect to app
            if ($user->isGuest() && $user->is_2fa) {
                return redirect()->route('index');
            }

            // 2. If user is ADMIN → skip 2FA, go to Filament
            if ($user->isAdmin()) {
                return redirect()->intended(\Filament\Facades\Filament::getUrl());
            }

            // 3. If Resorts Admin but no assigned resort → logout + warning
            if (
                $user->isResortsAdmin() &&
                ! $user->is_validated
            ) {
                auth()->logout();

                Notification::make()
                    ->title('Account Not Validated')
                    ->icon('heroicon-o-exclamation-circle')
                    ->body('Please contact administrator.')
                    ->warning()
                    ->send();

                return redirect('app');
            }

            // 4. Regular authenticated user → require 2FA
            if ($user->isGuest()) {
                $user->generateCode();
            }

            return redirect()->route('2fa.index');
        }

        // Not logged in → default behavior
        return parent::toResponse($request);
    }
}
