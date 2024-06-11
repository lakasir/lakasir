<?php

use App\Models\Tenants\User;
use Filament\Facades\Filament;

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
