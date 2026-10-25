<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetAdminLocale;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
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
            ->passwordReset()
            ->profile(isSimple: false)
            ->brandName('Elite Stone')
            ->brandLogo(asset('images/brand/logo-badge-navy.jpg'))
            ->brandLogoHeight('2.75rem')
            ->favicon(asset('favicon.ico'))
            ->colors([
                // Same navy scale as the public website (resources/css/app.css).
                'primary' => array_map(Color::convertToOklch(...), [
                    50 => '#f2f4f9', 100 => '#e3e8f2', 200 => '#c4cde2', 300 => '#94a3c7', 400 => '#6075a6',
                    500 => '#3f5488', 600 => '#2e4273', 700 => '#233565', 800 => '#1b2a52', 900 => '#16213f', 950 => '#0d1428',
                ]),
                'gray' => Color::Stone,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'info' => Color::Sky,
            ])
            ->font('IBM Plex Sans Arabic')
            ->maxContentWidth(Width::SevenExtraLarge)
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->navigationGroups([
                NavigationGroup::make(fn () => __('admin.groups.requests')),
                NavigationGroup::make(fn () => __('admin.groups.content')),
                NavigationGroup::make(fn () => __('admin.groups.settings')),
            ])
            ->navigationItems([
                NavigationItem::make('website')
                    ->label(fn () => __('admin.view_website'))
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn () => url('/'.app()->getLocale()), shouldOpenInNewTab: true)
                    ->sort(100),
            ])
            ->userMenuItems([
                Action::make('language')
                    ->label(fn () => app()->getLocale() === 'ar' ? __('admin.switch_to_en') : __('admin.switch_to_ar'))
                    ->icon('heroicon-o-language')
                    ->url(fn () => route('admin.locale', app()->getLocale() === 'ar' ? 'en' : 'ar')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            // Persistent so Livewire requests (table actions, modals) also use the admin's language.
            ->middleware([SetAdminLocale::class], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
