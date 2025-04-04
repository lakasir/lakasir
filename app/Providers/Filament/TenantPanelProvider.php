<?php

namespace App\Providers\Filament;

use App\Features\Member;
use App\Features\PaymentMethod;
use App\Features\PosV2;
use App\Features\Receivable;
use App\Features\Supplier;
use App\Filament\Clusters\Financials;
use App\Filament\Clusters\Inventory;
use App\Filament\Clusters\Reports;
use App\Filament\Clusters\Sales;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Users;
use App\Filament\Tenant\Pages\CartItem;
use App\Filament\Tenant\Pages\Cashier;
use App\Filament\Tenant\Pages\POS;
use App\Filament\Tenant\Pages\TenantLogin;
use App\Filament\Tenant\Resources\MemberResource;
use App\Filament\Tenant\Resources\PaymentMethodResource;
use App\Filament\Tenant\Resources\ReceivableResource;
use App\Filament\Tenant\Resources\SupplierResource;
use App\Http\Middleware\LocalizationMiddleware;
use App\Models\Tenants\About;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\DatePicker;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Resources\Resource;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

class TenantPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();
        DatePicker::configureUsing(function (DatePicker $datePicker): void {
            $datePicker
                ->closeOnDateSelection()
                ->native(false);
        });

    }

    public function panel(Panel $panel): Panel
    {
        $panel = $this->configurePanel($panel);
        $this->initializeConfigDefault($panel);

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn () => view('meta')
        );

        if (app()->environment('demo')) {
            $arraySupport = [
                'https://saweria.co/sheenazien',
                'https://trakteer.id/sheenazien8/tip',
                'https://buymeacoffee.com/sheenazien8',
            ];
            FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_START,
                fn (): View => view('donation-banner', [
                    'link' => Arr::random($arraySupport),
                ]),
            );
        }

        return $panel;
    }

    private function configurePanel(Panel $panel): Panel
    {
        $panel
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->darkMode(config('app.dark_mode', true))
            ->databaseNotifications()
            ->id('tenant')
            ->viteTheme('resources/css/filament/tenant/theme.css')
            ->colors(['primary' => Color::hex('#FF6600')])
            ->assets([
                Js::make('custom-javascript', resource_path('js/app.js')),
                Js::make('printer', resource_path('js/printer.js')),
                Js::make('indexeddb', resource_path('js/indexeddb.js')),
            ])
            ->favicon(url('favicon.ico'))
            ->spa(config('app.spa_mode'))
            ->authGuard('web')
            ->path('/member')
            ->login(TenantLogin::class)
            ->navigation(fn (NavigationBuilder $navigationBuilder) => $this->buildNavigation($navigationBuilder))
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\\Filament\\Tenant\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\\Filament\\Tenant\\Pages')
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\\Filament\\Tenant\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->middleware($this->getMiddleware())
            ->authMiddleware([Authenticate::class])
            ->pages([
                CartItem::class,
            ]);

        return $panel;
    }

    private function buildNavigation(NavigationBuilder $navigationBuilder): NavigationBuilder
    {
        $navigationBuilder
            ->items(array_filter($this->getNavigationItems(), fn ($item) => $item != null));

        if (module_plugin_exist()) {
            $navigationBuilder
                ->groups(\Lakasir\LakasirModule\Facades\LakasirModule::navigationGroups());
        }

        return $navigationBuilder;
    }

    private function getNavigationItems(): array
    {
        return [
            ...Pages\Dashboard::getNavigationItems(),
            $this->generateNavigationItem(Cashier::class),
            $this->generateNavigationItem(POS::class, PosV2::class),
            $this->generateNavigationItem(Sales::class),
            $this->generateNavigationItem(Financials::class),
            // $this->generateNavigationItem(SupplierResource::class, Supplier::class),
            // $this->generateNavigationItem(MemberResource::class, Member::class),
            // $this->generateNavigationItem(PaymentMethodResource::class, PaymentMethod::class),
            // $this->generateNavigationItem(ReceivableResource::class, Receivable::class),
            $this->generateNavigationItem(Inventory::class),
            $this->generateNavigationItem(Users::class),
            $this->generateNavigationItem(Reports::class),
            $this->generateNavigationItem(Settings::class),
            module_plugin_exist() ? $this->generateNavigationItem(\Lakasir\LakasirModule\Filament\Clusters\Modules::class) : null,
        ];
    }

    private function getMiddleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
            LocalizationMiddleware::class,
        ];
    }

    private function initializeConfigDefault(Panel $panel): void
    {
        if (Schema::hasTable('abouts') && $about = About::first()) {
            $panel->brandName($about->shop_name ?? 'Your Brand')
                ->brandLogo($about->photo ?? null);
        }
    }

    private function generateNavigationItem(string $resource, ?string $feature = null, ?array $activeWhen = []): NavigationItem
    {
        $canAccess = $feature ? feature($feature) && $resource::canAccess() : $resource::canAccess();

        $active = false;
        if ((new $resource) instanceof Page) {
            $active = Str::of($resource::getRouteName())->exactly(Route::current()->getName());
        }

        if ((new $resource) instanceof Resource) {
            $active = Str::of(Route::currentRouteName())->contains($resource::getRouteBaseName());
        }

        if ((new $resource) instanceof Cluster) {
            $active = Str::of(Route::currentRouteName())->contains($resource::getRouteName());
        }

        if (count($activeWhen) > 0) {
            $activatedRoute = [];
            foreach ($activeWhen as $resourceClass) {
                $activatedRoute[] = $resourceClass::getRouteName();
            }
            $activatedRoute[] = $resource::getRouteName();
            $active = in_array(Route::current()->getName(), $activatedRoute);
        }

        return NavigationItem::make($resource::getLabel())
            ->visible($canAccess)
            ->icon($resource::getNavigationIcon())
            ->isActiveWhen(fn (): bool => $active)
            ->url(fn (): string => $resource::getUrl());
    }
}
