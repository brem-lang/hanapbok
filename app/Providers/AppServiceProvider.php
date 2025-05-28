<?php

namespace App\Providers;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as ContractsLoginResponse;
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

        Livewire::component('validate-page', \App\Livewire\ValidationPage::class);
        Livewire::component('book-resort', \App\Livewire\BookResort::class);

        // resort admiun
        Livewire::component('entrance-fee-form', \App\Livewire\EntranceFees::class);
        Livewire::component('item', \App\Livewire\Items::class);
    }
}
