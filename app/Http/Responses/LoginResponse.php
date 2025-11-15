<?php

namespace App\Http\Responses;

use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();
        if (Auth::check()) {

            if (
                $user->isResortsAdmin() &&
                ! $user->AdminResort?->id
            ) {
                auth()->logout();

                Notification::make()
                    ->title('No Resort Assigned')
                    ->icon('heroicon-o-exclamation-circle')
                    ->body('Please contact administrator.')
                    ->warning()
                    ->send();

                return redirect('app');
            }

            auth()->user()->generateCode();

            return redirect()->route('2fa.index');
        }

        return parent::toResponse($request);
    }
}
