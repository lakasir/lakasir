<?php

namespace App\Providers\Filament;

use App\Features\Member;
use App\Features\PaymentMethod;
use App\Features\Permission;
use App\Features\Purchasing;
use App\Features\Receivable;
use App\Features\Role;
use App\Features\StockOpname;
use App\Features\Supplier;
use App\Features\User;
use App\Features\Voucher;
use App\Filament\Tenant\Pages\Cashier;
use App\Filament\Tenant\Pages\CashierReport;
use App\Filament\Tenant\Pages\EditProfile;
use App\Filament\Tenant\Pages\GeneralSetting;
use App\Filament\Tenant\Pages\Printer;
use App\Filament\Tenant\Pages\ProductReport;
use App\Filament\Tenant\Pages\PurchasingReport;
use App\Filament\Tenant\Pages\Report;
use App\Filament\Tenant\Pages\SellingReport;
use App\Filament\Tenant\Pages\TenantLogin;
use App\Filament\Tenant\Resources\CategoryResource;
use App\Filament\Tenant\Resources\MemberResource;
use App\Filament\Tenant\Resources\PaymentMethodResource;
use App\Filament\Tenant\Resources\PermissionResource;
use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\PurchasingResource;
use App\Filament\Tenant\Resources\ReceivableResource;
use App\Filament\Tenant\Resources\RoleResource;
use App\Filament\Tenant\Resources\SellingResource;
use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\SupplierResource;
use App\Filament\Tenant\Resources\TableResource;
use App\Filament\Tenant\Resources\UserResource;
use App\Filament\Tenant\Resources\VoucherResource;
use App\Http\Middleware\LocalizationMiddleware;
use App\Models\Tenants\About;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
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
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $this->configurePanel($panel);

        $this->initializeConfigDefault($panel);

        if (module_plugin_exist()) {
            $panel->plugin(\Lakasir\LakasirModule\LakasirModulePlugin::make());
        }

        return $panel;
    }

    private function configurePanel(Panel $panel): Panel
    {
        $panel
            ->darkMode(config('app.dark_mode', true))
            ->databaseNotifications()
            ->id('tenant')
            ->viteTheme('resources/css/filament/tenant/theme.css')
            ->colors(['primary' => Color::hex('#FF6600')])
            ->assets([
                Js::make('custom-javascript', resource_path('js/app.js')),
                Js::make('printer', resource_path('js/printer.js')),
            ])
            ->favicon(url('favicon.ico'))
            ->spa(config('app.spa_mode'))
            ->authGuard('web')
            ->path('/member')
            ->login(TenantLogin::class)
            ->navigation(fn (NavigationBuilder $navigationBuilder) => $this->buildNavigation($navigationBuilder))
            ->profile(EditProfile::class)
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\\Filament\\Tenant\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\\Filament\\Tenant\\Pages')
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\\Filament\\Tenant\\Widgets')
            ->middleware($this->getMiddleware())
            ->authMiddleware([Authenticate::class]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn (): string => Blade::render('@livewire(\'forms.global.localization-selector\')')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn (): string => Blade::render('@livewire(\'forms.global.timezone-select\')')
        );

        return $panel;
    }

    private function buildNavigation(NavigationBuilder $navigationBuilder): NavigationBuilder
    {
        $navigationBuilder->groups($this->getNavigationGroups());
        if (module_plugin_exist()) {
            $navigationBuilder
                ->groups(\Lakasir\LakasirModule\LakasirModulePlugin::make()->navigationGroups());
        }

        return $navigationBuilder
            ->items(array_filter($this->getNavigationItems(), fn ($item) => $item != null));

    }

    private function getNavigationItems(): array
    {
        return [
            ...Pages\Dashboard::getNavigationItems(),
            $this->generateNavigationItem(Cashier::class),
            $this->generateNavigationItem(SellingResource::class),
            $this->generateNavigationItem(SupplierResource::class, Supplier::class),
            $this->generateNavigationItem(MemberResource::class, Member::class),
            $this->generateNavigationItem(PaymentMethodResource::class, PaymentMethod::class),
            $this->generateNavigationItem(ReceivableResource::class, Receivable::class),
        ];
    }

    private function getNavigationGroups(): array
    {
        return [
            NavigationGroup::make(__('Inventory'))->items([
                $this->generateNavigationItem(PurchasingResource::class, Purchasing::class),
                $this->generateNavigationItem(StockOpnameResource::class, StockOpname::class),
                $this->generateNavigationItem(ProductResource::class),
                $this->generateNavigationItem(CategoryResource::class),
                $this->generateNavigationItem(TableResource::class)->hidden(About::first() && About::first()->business_type != 'fnb'),
            ]),
            NavigationGroup::make(__('User'))->items([
                $this->generateNavigationItem(UserResource::class, User::class),
                $this->generateNavigationItem(RoleResource::class, Role::class),
                $this->generateNavigationItem(PermissionResource::class, Permission::class),
            ]),
            NavigationGroup::make(__('Report'))->label('')->collapsible(false)->items([
                $this->generateNavigationItem(
                    resource: Report::class,
                    activeWhen: [
                        SellingReport::class,
                        ProductReport::class,
                        CashierReport::class,
                        PurchasingReport::class,
                    ]
                ),
            ]),
            NavigationGroup::make(__('General'))->label('')->collapsible(false)->items([
                $this->generateNavigationItem(VoucherResource::class, Voucher::class),
            ]),
            NavigationGroup::make(__('Setting'))->collapsible(false)->items([
                $this->generateNavigationItem(GeneralSetting::class),
                $this->generateNavigationItem(Printer::class),
            ]),
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
