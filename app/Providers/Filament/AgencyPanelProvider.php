<?php

namespace App\Providers\Filament;

use App\Filament\Agency\Pages\AgencyDashboard;
use App\Http\Middleware\RedirectToAppLogin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AgencyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('agency')
            ->path('agency')
            ->login(false)
            ->homeUrl('/agency')
            ->broadcasting(false)
            ->sidebarCollapsibleOnDesktop(false)
            ->brandName('HERO Agency Portal')
            ->brandLogo(fn (): Htmlable => new HtmlString(view('filament.branding.brand')->render()))
            ->brandLogoHeight('2.5rem')
            ->maxContentWidth(Width::Full)
            ->colors(['primary' => Color::Amber])
            ->discoverResources(in: app_path('Filament/Agency/Resources'), for: 'App\Filament\Agency\Resources')
            ->discoverWidgets(in: app_path('Filament/Agency/Widgets'), for: 'App\Filament\Agency\Widgets')
            ->pages([AgencyDashboard::class])
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
            ->authMiddleware([RedirectToAppLogin::class])
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />'
                    .'<style id="hero-filament-fix">'
                    .'html,body{height:auto!important;min-height:100dvh!important}'
                    .'body.fi-body{display:contents!important;overflow:visible!important}'
                    .'html.fi .fi-topbar-ctn,html.fi .fi-layout,html.fi .fi-main-ctn,html.fi .fi-sidebar,html.fi .fi-topbar{opacity:1!important;visibility:visible!important}'
                    .'html.fi .fi-main-ctn,html.fi .fi-layout{display:flex!important}'
                    .'html.fi .fi-main-ctn{min-height:calc(100dvh - 4rem)!important}'
                    .'html.fi .fi-sidebar{transform:translateX(0)!important;width:var(--sidebar-width,20rem)!important}'
                    .'html.fi .fi-topbar-start{display:flex!important}'
                    .'html.fi [x-cloak],html.fi [x-cloak=""],html.fi [x-cloak="x-cloak"],html.fi [x-cloak="-lg"],html.fi [x-cloak="-lg"],html.fi [x-cloak="lg"]{display:revert!important}'
                    .'html.fi .fi-sidebar-close-overlay{display:none!important}'
                    .'</style>',
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />'
                    .'<link href="'.asset('css/hero-admin-live.css').'?v=7" rel="stylesheet" />',
            );
    }
}
