<?php

namespace App\Providers\Filament;

use App\Filament\Courier\Pages\Auth\CourierLogin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CourierPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('courier')
            ->path('courier')
            ->login(CourierLogin::class)
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
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('
                    <style>
                        /* Filament Native Theme Adaptations */
                        .dark body { background-color: #0f172a !important; }
                        .dark .fi-main { background-color: #0f172a !important; }
                        
                        /* === CARD STYLING === */
                        /* Light Mode Card styling */
                        .fi-ta-record { 
                            border-radius: 1.25rem !important; 
                            margin-bottom: 0.75rem !important; 
                            border: 1px solid #e2e8f0 !important; 
                            background-color: #ffffff !important;
                            overflow: hidden; 
                            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
                        }
                        
                        /* Dark Mode Card styling override */
                        .dark .fi-ta-record { 
                            background-color: #1e293b !important; 
                            border: 1px solid #334155 !important;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important; 
                        }
                        
                        /* Stats Card overrides */
                        .dark .fi-wi-stats-overview-stat { 
                            background-color: #1e293b !important; 
                            border: 1px solid #334155 !important;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important; 
                        }
                        
                        /* Button styling */
                        .dark .fi-btn-color-primary { 
                            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2) !important; 
                        }
                        
                        /* === ACTION BUTTON LAYOUT === */
                        /* Hide default Filament actions since we use our custom inline buttons */
                        .fi-ta-actions { display: none !important; }
                        
                        /* === COURIER BATCH BOX STYLING === */
                        .courier-batch-box {
                            background-color: rgba(0, 0, 0, 0.03) !important;
                            border: 1px solid rgba(0, 0, 0, 0.08) !important;
                            border-radius: 12px !important;
                            padding: 12px !important;
                            margin-bottom: 12px !important;
                        }
                        .dark .courier-batch-box {
                            background-color: rgba(255, 255, 255, 0.03) !important;
                            border: 1px solid rgba(255, 255, 255, 0.08) !important;
                        }
                        
                        .courier-batch-item {
                            background-color: rgba(0, 0, 0, 0.02) !important;
                            border: 1px solid rgba(0, 0, 0, 0.05) !important;
                            border-left: 4px solid #ea580c !important;
                            border-radius: 8px !important;
                            padding: 8px 10px !important;
                            margin-bottom: 6px !important;
                        }
                        .dark .courier-batch-item {
                            background-color: rgba(255, 255, 255, 0.05) !important;
                            border: 1px solid rgba(255, 255, 255, 0.1) !important;
                            border-left: 4px solid #f97316 !important;
                        }
                        
                        .courier-batch-title { 
                            color: #ea580c !important; 
                            font-size: 0.75rem !important; 
                            font-weight: 700 !important; 
                            text-transform: uppercase !important; 
                            letter-spacing: 0.05em !important; 
                            margin-bottom: 8px !important; 
                        }
                        .dark .courier-batch-title { 
                            color: #f97316 !important; 
                        }
                        
                        .courier-batch-text-main { 
                            color: #1f2937; 
                            font-size: 0.82rem !important; 
                            font-weight: 600 !important; 
                        }
                        .dark .courier-batch-text-main { 
                            color: #f3f4f6; 
                        }
                        
                        .courier-batch-text-sub { 
                            color: #6b7280;
                            font-size: 0.72rem !important; 
                            margin-top: 2px !important; 
                        }
                        .dark .courier-batch-text-sub { 
                            color: #9ca3af; 
                        }
                        
                        @media (max-width: 1024px) {
                            .fi-sidebar { display: none !important; }
                            .fi-topbar { display: none !important; }
                            .fi-main { padding-bottom: 90px !important; padding-top: 1rem !important; }
                            
                            /* Stats grid styling */
                            .fi-wi-stats-overview-stat { flex: 1; min-width: 0; }
                            .fi-wi-stats-overview > div { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 0.75rem !important; }
                            .fi-wi-stats-overview-stat-label { font-size: 0.78rem !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                            .fi-wi-stats-overview-stat-description { font-size: 0.7rem !important; }
                            .fi-wi-stats-overview-stat-value { font-size: 1.8rem !important; }
                            
                            /* Prevents unwanted overflow scroll */
                            .fi-ta-table-wrapper { overflow-x: hidden !important; }
                            .fi-ta-table { min-width: unset !important; width: 100% !important; table-layout: fixed !important; }
                            .fi-ta-content { overflow: hidden !important; }
                            .fi-wi-table { overflow: hidden !important; }
                            
                            /* Make cards span full width in mobile */
                            .fi-ta-record { width: 100% !important; box-sizing: border-box !important; }
                            .fi-ta-col { width: 100% !important; max-width: 100% !important; }
                            
                            .fi-ta-header-cell { display: none !important; }
                        }
                        
                        /* Custom Bottom Nav CSS */
                        .courier-bottom-nav {
                            position: fixed; bottom: 0; left: 0; width: 100%; height: 75px; z-index: 50;
                            background-color: #ffffff;
                            border-top: 1px solid #e2e8f0;
                            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05);
                            display: flex; justify-content: space-around; align-items: center;
                            border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem;
                        }
                        
                        /* Dark Mode Bottom Nav override */
                        .dark .courier-bottom-nav { 
                            background-color: #1e293b; 
                            border-top: 1px solid #334155;
                            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.3); 
                        }
                        
                        @media (min-width: 1024px) { 
                            .courier-bottom-nav { display: none !important; } 
                        }
                        
                        .courier-nav-item {
                            display: flex; flex-direction: column; align-items: center; justify-content: center;
                            width: 100%; height: 100%; text-decoration: none; color: #64748b; background: transparent; border: none; cursor: pointer; padding: 0; margin: 0;
                            transition: color 0.15s ease-in-out;
                        }
                        
                        .courier-nav-item:hover, .courier-nav-item:focus, .courier-nav-item.active { 
                            color: #ea580c; 
                        }
                        
                        /* Dark Mode Active Item colors */
                        .dark .courier-nav-item {
                            color: #94a3b8;
                        }
                        .dark .courier-nav-item:hover, .dark .courier-nav-item:focus, .dark .courier-nav-item.active { 
                            color: #f97316; 
                        }
                        
                        .courier-nav-item svg { width: 24px; height: 24px; margin-bottom: 4px; }
                        .courier-nav-item span { font-size: 11px; font-weight: 600; }
                    </style>
                ')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('filament.courier.bottom-nav')
            );
    }
}
