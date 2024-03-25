<?php

namespace App\Providers\Filament;

use App\Filament\Tenant\Pages\EditProfile;
use App\Tenant;
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
use Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper;

class TenantPanelProvider extends PanelProvider
{
    public static $abortRequest;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('tenant')
            ->colors([
                'primary' => Color::hex('#FF6600'),
            ])
            ->spa()
            ->authGuard('tenant')
            ->path('/member')
            ->login()
            ->profile(EditProfile::class)
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\\Filament\\Tenant\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\\Filament\\Tenant\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\\Filament\\Tenant\\Widgets')
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
            ]);

        $url = request()->getHost();
        if (in_array($url, config('tenancy.central_domains'))) {
            return $panel;
        }
        $domain = explode('.'.config('tenancy.central_domains')[0], $url);
        $domain = explode('.', $domain[0]);
        if (! in_array($domain[0], ['', 'localhost', config('tenancy.central_domains')[0]])) {
            if ($domain[0] === 'www') {
                $domain[0] = $domain[1];
            }
            $tenant = Tenant::find($domain[0]);
            if (! $tenant) {
                abort(404);
            }
            tenancy()->initialize($domain[0]);
            $about = $tenant?->user?->about;
            $subdomain = $tenant?->domains?->first()?->domain;
            $panel
                ->brandName($about->shop_name ?? 'Your Brand')
                ->brandLogo($about->photo ?? null)
                ->domain($subdomain);

            $db = app(DatabaseTenancyBootstrapper::class);
            $db->bootstrap($tenant);

        }

        return $panel;
    }
}
