<?php

namespace App\Providers\Filament;

use App\Features\Debt;
use App\Features\Member;
use App\Features\PaymentMethod;
use App\Features\Permission;
use App\Features\Purchasing;
use App\Features\Role;
use App\Features\Setting;
use App\Features\StockOpname;
use App\Features\Voucher;
use App\Filament\Tenant\Pages\About as PagesAbout;
use App\Filament\Tenant\Pages\Cashier;
use App\Filament\Tenant\Pages\CashierReport;
use App\Filament\Tenant\Pages\EditProfile;
use App\Filament\Tenant\Pages\Printer;
use App\Filament\Tenant\Pages\SellingReport;
use App\Filament\Tenant\Pages\Settings;
use App\Filament\Tenant\Pages\TenantLogin;
use App\Filament\Tenant\Resources\CategoryResource;
use App\Filament\Tenant\Resources\DebtResource;
use App\Filament\Tenant\Resources\MemberResource;
use App\Filament\Tenant\Resources\PaymentMethodResource;
use App\Filament\Tenant\Resources\PermissionResource;
use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\PurchasingResource;
use App\Filament\Tenant\Resources\RoleResource;
use App\Filament\Tenant\Resources\SellingResource;
use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\UserResource;
use App\Filament\Tenant\Resources\VoucherResource;
use App\Models\Tenants\About;
use App\Tenant;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
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
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper;

class TenantPanelProvider extends PanelProvider
{
    public static $abortRequest;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('tenant')
            ->viteTheme('resources/css/filament/tenant/theme.css')
            ->colors([
                'primary' => Color::hex('#FF6600'),
            ])
            ->spa(config('app.spa_mode'))
            ->authGuard('web')
            ->path('/member')
            ->login(TenantLogin::class)
            ->navigation(function (NavigationBuilder $navigationBuilder) {
                return $navigationBuilder
                    ->items([
                        ...Pages\Dashboard::getNavigationItems(),
                        ...(hasFeatureAndPermission(Member::class, 'read member') ? MemberResource::getNavigationItems() : []),
                        ...(can('read category') ? CategoryResource::getNavigationItems() : []),
                        ...(hasFeatureAndPermission(PaymentMethod::class, 'read payment method') ? PaymentMethodResource::getNavigationItems() : []),
                        ...(can('read product') ? ProductResource::getNavigationItems() : []),
                        ...(hasFeatureAndPermission(Purchasing::class, 'read purchasing') ? PurchasingResource::getNavigationItems() : []),
                        ...(hasFeatureAndPermission(StockOpname::class, 'read stock opname') ? StockOpnameResource::getNavigationItems() : []),
                        ...(hasFeatureAndPermission(Debt::class, 'read debt') ? DebtResource::getNavigationItems() : []),
                    ])
                    ->groups([
                        NavigationGroup::make('Transaction')
                            ->items([
                                ...(can('read selling') ? SellingResource::getNavigationItems() : []),
                                ...(can('create selling') ? Cashier::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('User'))
                            ->items([
                                ...(can('read user') ? UserResource::getNavigationItems() : []),
                                ...(hasFeatureAndPermission(Role::class, 'read role') ? RoleResource::getNavigationItems() : []),
                                ...(hasFeatureAndPermission(Permission::class, 'read permission') ? PermissionResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('Report'))
                            ->items([
                                ...(can('generate selling report') ? SellingReport::getNavigationItems() : []),
                                ...(can('generate cashier report') ? CashierReport::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('General'))
                            ->collapsible(false)
                            ->items([
                                ...(hasFeatureAndPermission(Voucher::class, 'read voucher') ? VoucherResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('Setting'))
                            ->collapsible(false)
                            ->items([
                                ...Printer::getNavigationItems(),
                                ...(hasFeatureAndPermission(Setting::class) ? Settings::getNavigationItems() : []),
                            ]),
                    ]);

            })
            ->userMenuItems([
                MenuItem::make()
                    ->label(fn (): string => PagesAbout::getNavigationLabel())
                    ->url(fn (): string => PagesAbout::getUrl())
                    ->icon(PagesAbout::getNavigationIcon()),
            ])
            ->profile(EditProfile::class)
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\\Filament\\Tenant\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\\Filament\\Tenant\\Pages')
            ->pages([
                Pages\Dashboard::class,
                Settings::class,
                SellingReport::class,
                CashierReport::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\\Filament\\Tenant\\Widgets')
            ->widgets([
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
            ]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn (): string => Blade::render('@livewire(\'forms.global.timezone-select\')'),
        );
        $url = request()->getHost();
        if (config('tenancy.central_domains')[0] === null) {
            return $panel;
        }
        $tenant = Tenant::whereHas('domains', function ($query) use ($url) {
            $query->where('domain', $url);
        })->first();

        if ($tenant) {
            if (! $tenant) {
                abort(404);
            }
            tenancy()->initialize($tenant->id);
            $subdomain = $tenant?->domains()->where('domain', $url)->first()?->domain;
            $panel
                ->domain($subdomain);
            config(['cache.prefix' => $subdomain.'_']);

            $db = app(DatabaseTenancyBootstrapper::class);
            $db->bootstrap($tenant);

            tenant()->run(function () use ($panel) {
                $about = About::first();

                $panel
                    ->brandName($about->shop_name ?? 'Your Brand')
                    ->brandLogo($about->photo ?? null);
            });

        }

        return $panel;
    }
}
