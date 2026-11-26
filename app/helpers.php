<?php

use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Number;
use Lakasir\LakasirModule\Events\TransactionSucceed;

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

if (! function_exists('module_plugin_exist')) {
    /**
     * Whether the lakasir-module runtime is installed, mirroring the check
     * PR #222 used. Gates the module navigation entry and module hooks.
     */
    function module_plugin_exist(): bool
    {
        return class_exists(TransactionSucceed::class);
    }
}
