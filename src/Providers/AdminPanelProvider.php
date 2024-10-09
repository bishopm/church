<?php

namespace Bishopm\Church\Providers;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Bishopm\Church\Filament\Widgets\PastoralContact;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Bishopm\Church\Filament\Widgets\RecentSongs;
use Bishopm\Church\Filament\Widgets\MeasuresChart;
use Bishopm\Church\Filament\Widgets\NewMembers;
use Bishopm\Church\Filament\Widgets\StatsOverview;
use Bishopm\Church\Filament\Widgets\TasksToDo;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Teal
            ])
            ->discoverResources(in: base_path('vendor/bishopm/church/src/Filament/Resources'), for: 'Bishopm\\Church\\Filament\\Resources')
            ->discoverPages(in: base_path('vendor/bishopm/church/src/Filament/Pages'), for: 'Bishopm\\Church\\Filament\\Pages')
            ->discoverClusters(in: base_path('vendor/bishopm/church/src/Filament/Clusters'), for: 'Bishopm\\Church\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->plugins([
                FilamentSpatieRolesPermissionsPlugin::make(),
                \Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin::make()
                    ->pages([
                    ])
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                RecentSongs::class,
                PastoralContact::class,
                MeasuresChart::class,
                NewMembers::class,
                StatsOverview::class,
                TasksToDo::class
            ])
            ->sidebarCollapsibleOnDesktop()
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
            ->userMenuItems([
                MenuItem::make()
                    ->label('Website')
                    ->url('/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
                MenuItem::make()
                    ->label('Settings')
                    ->url('/admin/settings')
                    ->visible(fn (): bool => auth()->user()->isSuperAdmin())
                    ->icon('heroicon-o-cog-8-tooth'),
            ]);
    }
}
