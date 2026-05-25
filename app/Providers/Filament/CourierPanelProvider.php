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

class CourierPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('courier')
            ->path('courier')
            ->login(\App\Filament\Courier\Pages\Auth\CourierLogin::class)
            ->colors([
                'primary' => Color::Orange,
            ])
            ->font('Outfit')
            ->brandLogo(fn () => view('filament.logo'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('assets/logo_indoroster_no_text.PNG'))
            ->discoverResources(in: app_path('Filament/Courier/Resources'), for: 'App\\Filament\\Courier\\Resources')
            ->discoverPages(in: app_path('Filament/Courier/Pages'), for: 'App\\Filament\\Courier\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Courier/Widgets'), for: 'App\\Filament\\Courier\\Widgets')
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
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('
                    <style>
                        /* Deep Dark Theme overrides to match mockup */
                        .dark body { background-color: #1f222a !important; }
                        .dark .fi-main { background-color: #1f222a !important; }
                        .dark .fi-ta-record { background-color: #262a34 !important; border-radius: 1.5rem !important; margin-bottom: 1rem !important; border: none !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important; }
                        .dark .fi-wi-stats-overview-stat { background-color: #262a34 !important; border-radius: 1.5rem !important; border: none !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important; }
                        
                        /* Button glow */
                        .dark .fi-btn-color-primary { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3) !important; border-radius: 2rem !important; font-weight: bold !important; text-transform: uppercase !important; }
                        .dark .fi-btn { border-radius: 2rem !important; }
                        
                        /* === CARD STYLING === */
                        /* Each card: dark bg, rounded, spaced */
                        .fi-ta-record { border-radius: 1.25rem !important; margin-bottom: 0.75rem !important; border: 1px solid rgba(255,255,255,0.06) !important; overflow: hidden; }
                        .dark .fi-ta-record { background-color: #23272f !important; box-shadow: 0 2px 12px rgba(0,0,0,0.4) !important; }
                        
                        /* === ACTION BUTTON LAYOUT === */
                        /* Sembunyikan tombol bawaan Filament - kita pakai tombol kustom di dalam kartu */
                        .fi-ta-actions { display: none !important; }
                        
                        @media (max-width: 1024px) {
                            .fi-sidebar { display: none !important; }
                            .fi-topbar { display: none !important; }
                            .fi-main { padding-bottom: 80px !important; padding-top: 1rem !important; }
                            
                            /* Stats: 2 kolom berdampingan */
                            .fi-wi-stats-overview-stat { flex: 1; min-width: 0; }
                            .fi-wi-stats-overview > div { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 0.75rem !important; }
                            .fi-wi-stats-overview-stat-label { font-size: 0.78rem !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                            .fi-wi-stats-overview-stat-description { font-size: 0.7rem !important; }
                            .fi-wi-stats-overview-stat-value { font-size: 1.8rem !important; }
                            
                            /* FIX: Hapus horizontal scroll pada tabel/widget */
                            .fi-ta-table-wrapper { overflow-x: hidden !important; }
                            .fi-ta-table { min-width: unset !important; width: 100% !important; table-layout: fixed !important; }
                            .fi-ta-content { overflow: hidden !important; }
                            .fi-wi-table { overflow: hidden !important; }
                            
                            /* Pastikan kartu menyesuaikan lebar layar */
                            .fi-ta-record { width: 100% !important; box-sizing: border-box !important; }
                            .fi-ta-col { width: 100% !important; max-width: 100% !important; }
                            
                            .fi-ta-header-cell { display: none !important; }
                        }
                        
                        /* Custom Bottom Nav CSS */
                        .courier-bottom-nav {
                            position: fixed; bottom: 0; left: 0; width: 100%; height: 75px; z-index: 50;
                            background-color: #262a34; /* dark mode fallback */
                            border-top: none;
                            box-shadow: 0 -4px 10px rgba(0,0,0,0.3);
                            display: flex; justify-content: space-around; align-items: center;
                            border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem;
                        }
                        @media (prefers-color-scheme: light) {
                            .courier-bottom-nav { background-color: #ffffff; box-shadow: 0 -4px 10px rgba(0,0,0,0.05); }
                        }
                        @media (min-width: 1024px) { .courier-bottom-nav { display: none !important; } }
                        
                        .courier-nav-item {
                            display: flex; flex-direction: column; align-items: center; justify-content: center;
                            width: 100%; height: 100%; text-decoration: none; color: #a1a1aa; background: transparent; border: none; cursor: pointer; padding: 0; margin: 0;
                        }
                        .courier-nav-item:hover, .courier-nav-item:focus { color: #f59e0b; }
                        .courier-nav-item svg { width: 24px; height: 24px; margin-bottom: 4px; }
                        .courier-nav-item span { font-size: 11px; font-weight: 500; }
                    </style>
                ')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('filament.courier.bottom-nav')
            );
    }
}
