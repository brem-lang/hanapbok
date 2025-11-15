<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as ContractsLoginResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ContractsLoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);

        FilamentColor::register([
            'primary' => Color::Emerald,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): string => ucfirst(auth()->user()->role),
        );

        Filament::serving(function () {
            Filament::registerRenderHook(
                'panels::auth.login.form.after',
                fn (): string => <<<'HTML'
                    <script>
                        // Find the button with type="submit" and change its text content
                        const button = document.querySelector('button[type="submit"]');
                        if (button) {
                            button.textContent = 'Login';
                        }

                         const heading = document.querySelector('h1.fi-simple-header-heading')
                        if (heading) {
                            heading.textContent = 'Login';
                        }
                    </script>
                HTML,
            );
        });

        Livewire::component('validate-page', \App\Livewire\ValidationPage::class);
        Livewire::component('book-resort', \App\Livewire\BookResort::class);

        // resort admiun
        Livewire::component('entrance-fee-form', \App\Livewire\EntranceFees::class);
        Livewire::component('item', \App\Livewire\Items::class);
    }
}
