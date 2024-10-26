<?php

use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
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

if (! function_exists('module_plugin_exist')) {
    function module_plugin_exist(): bool
    {
        return class_exists(\Lakasir\LakasirModule\Events\TransactionSucceed::class);
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

if (! function_exists('notification')) {
    function notification(?string $id = null): Notification
    {
        return Notification::make($id);
    }
}

if (! function_exists('price_string_to_float')) {
    function price_string_to_float(string $priceString): float
    {
        return (float) str($priceString)->replace(',', '')->value();
    }
}
