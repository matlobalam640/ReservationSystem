<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AdminDashboard;
use App\Filament\Pages\ReconciliationDashboard;
use App\Filament\Pages\WalkInCheckout;
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
use Illuminate\Support\HtmlString;
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
            ->login(false)
            ->homeUrl('/admin')
            ->broadcasting(false)
            ->sidebarCollapsibleOnDesktop(false)
            ->brandName('HERO Reservation System')
            ->brandLogo(fn (): Htmlable => new HtmlString(view('filament.branding.brand')->render()))
            ->brandLogoHeight('2.5rem')
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->pages([
                AdminDashboard::class,
                ReconciliationDashboard::class,
                WalkInCheckout::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Check-In')
                    ->url(fn () => route('check-in.index'))
                    ->icon('heroicon-o-clipboard-document-check')
                    ->group('Operations')
                    ->sort(10),
                \Filament\Navigation\NavigationItem::make('Manifest')
                    ->url(fn () => route('manifest.index'))
                    ->icon('heroicon-o-document-text')
                    ->group('Operations')
                    ->sort(11),
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
            ])
            ->authMiddleware([
                RedirectToAppLogin::class,
            ])
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
                    .'html.fi [x-cloak],html.fi [x-cloak=""],html.fi [x-cloak="x-cloak"],html.fi [x-cloak="-lg"],html.fi [x-cloak="lg"]{display:revert!important}'
                    .'html.fi .fi-sidebar-close-overlay{display:none!important}'
                    .'</style>',
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => '<script id="hero-filament-fallback">'
                    .'(function(){function s(){document.querySelectorAll(".fi-sidebar").forEach(function(e){e.classList.add("fi-sidebar-open")});'
                    .'document.querySelectorAll(".fi-main-ctn").forEach(function(e){e.style.setProperty("opacity","1","important");e.style.setProperty("display","flex","important")});'
                    .'document.querySelectorAll(".fi-topbar-ctn [x-cloak],.fi-sidebar[x-cloak]").forEach(function(e){e.removeAttribute("x-cloak")})}'
                    .'document.addEventListener("DOMContentLoaded",function(){setTimeout(s,1500)});'
                    .'if(document.readyState!=="loading")setTimeout(s,1500)})();'
                    .'</script>',
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />'
                    .'<link href="'.asset('css/hero-admin-live.css').'?v=8" rel="stylesheet" />',
            );
    }
}
