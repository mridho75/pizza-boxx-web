<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Import Models
use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductAddon;
use App\Models\Promo;
use App\Models\Order;

// Filament Navigation
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

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
                'primary' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups(array_filter([
                (auth()->check() && auth()->user()->hasRole('admin')) ? NavigationGroup::make('Manajemen Pusat')->items([
                    NavigationItem::make('Dashboard')
                        ->url(fn (): string => route('filament.admin.pages.dashboard'))
                        ->icon('heroicon-o-home')
                        ->activeIcon('heroicon-s-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard')),
                    NavigationItem::make('Users')
                        ->url(fn (): string => User::getUrl())
                        ->icon('heroicon-o-users')
                        ->activeIcon('heroicon-s-users')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.users.index')),
                    NavigationItem::make('Categories')
                        ->url(fn (): string => Category::getUrl())
                        ->icon('heroicon-o-tag')
                        ->activeIcon('heroicon-s-tag')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.categories.index')),
                    NavigationItem::make('Locations')
                        ->url(fn (): string => Location::getUrl())
                        ->icon('heroicon-o-building-storefront')
                        ->activeIcon('heroicon-s-building-storefront')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.locations.index')),
                    NavigationItem::make('Products')
                        ->url(fn (): string => Product::getUrl())
                        ->icon('heroicon-o-archive-box')
                        ->activeIcon('heroicon-s-archive-box')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.products.index')),
                    NavigationItem::make('Product Options')
                        ->url(fn (): string => ProductOption::getUrl())
                        ->icon('heroicon-o-adjustments-vertical')
                        ->activeIcon('heroicon-s-adjustments-vertical')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.product-options.index')),
                    NavigationItem::make('Product Addons')
                        ->url(fn (): string => ProductAddon::getUrl())
                        ->icon('heroicon-o-plus-circle')
                        ->activeIcon('heroicon-s-plus-circle')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.product-addons.index')),
                    NavigationItem::make('Promos')
                        ->url(fn (): string => Promo::getUrl())
                        ->icon('heroicon-o-gift')
                        ->activeIcon('heroicon-s-gift')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.promos.index')),
                ]) : null,
                (auth()->check() && auth()->user()->hasAnyRole(['admin', 'employee'])) ? NavigationGroup::make('Manajemen Pesanan')->items([
                    NavigationItem::make('Orders')
                        ->url(fn (): string => Order::getUrl())
                        ->icon('heroicon-o-shopping-bag')
                        ->activeIcon('heroicon-s-shopping-bag')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.orders.index')),
                ]) : null,
                (auth()->check() && auth()->user()->hasRole('employee')) ? NavigationGroup::make('Manajemen Cabang')->items([
                    // Tambahkan menu cabang di sini jika ada nanti
                ]) : null,
            ]));
    }
}
