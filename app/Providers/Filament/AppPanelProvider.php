<?php

namespace App\Providers\Filament;

use App\Http\Middleware\Check2FA;
use App\Livewire\Register;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                Check2FA::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems(
                [
                    NavigationItem::make('Booking Platform')
                        ->sort(2)
                        ->visible(fn () => auth()->user()->isGuest() && auth()->user()->is_validated)
                        // ->url($this->urlChecker().'/app/booking')
                        ->url("javascript:window.open('".$this->urlChecker()."/app/booking', '_blank');")
                        ->icon('heroicon-o-document-text')
                        ->activeIcon('heroicon-s-document-text'),
                ]
            )
            ->darkMode(false)
            ->spa()
            ->brandName('Booking Platform')
            ->registration(Register::class)
            ->maxContentWidth(MaxWidth::Full)
            ->databaseNotifications()
            ->brandLogo(fn () => view('filament.logo'))
            ->renderHook(
                'panels::auth.login.form.after',
                fn () => view('auth.socialite.google')
            );
    }

    public function urlChecker()
    {
        $url = config('app.env') == 'local' ? url('/') : config('app.url');

        return $url;
    }
}
