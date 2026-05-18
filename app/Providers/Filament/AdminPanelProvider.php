<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->loginRouteSlug('login')
            ->homeUrl('/')
            ->brandName('Badia Padel Tour')
            ->brandLogo(asset('assets/logo_electriclime.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('favicon.png'))
            ->colors([
                'primary' => [
                    50 => '#f9ffe6',
                    100 => '#f0ffc2',
                    200 => '#e6ff9e',
                    300 => '#dcff7a',
                    400 => '#d2ff56',
                    500 => '#c9ff00', // Electric Lime
                    600 => '#a8d600',
                    700 => '#87ad00',
                    800 => '#668400',
                    900 => '#455b00',
                    950 => '#2a3700',
                ],
                'gray' => [
                    50 => '#2a2a2a',
                    100 => '#242424',
                    200 => '#1e1e1e',
                    300 => '#181818',
                    400 => '#151515',
                    500 => '#111111', // Night Rider
                    600 => '#0d0d0d',
                    700 => '#0a0a0a',
                    800 => '#070707',
                    900 => '#040404',
                    950 => '#020202',
                ],
            ])
            ->darkMode(true)
            ->font('Gopher')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}