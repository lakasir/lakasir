<?php

use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Number;

if (! function_exists('hasFeatureAndPermission')) {
    function hasFeatureAndPermission(string $feature, ?string $permission = null): bool
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        if (! $permission) {
            return feature($feature);
        }

        return feature($feature) && $user->can($permission);
    }
}

if (! function_exists('can')) {
    function can(string $permission): bool
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        return $user->can($permission);
    }
}

if (! function_exists('isMultiTenant')) {
    function isMultiTenant(): bool
    {
        $central_domains = config('tenancy.central_domains');
        $admin_domains = config('tenancy.admin_domains');

        if (count($central_domains) > 0 && $central_domains[0] == request()->getHost()) {
            return in_array(request()->getHost(), $admin_domains);
        }

        return false;
    }
}

if (! function_exists('price_format')) {
    function price_format(float $price): string
    {
        return Number::currency(
            number: $price,
            in: Setting::get('currency', 'IDR'),
            locale: Profile::get()->locale ?? 'en'
        );
    }
}
