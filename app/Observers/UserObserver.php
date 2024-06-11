<?php

namespace App\Observers;

use App\Constants\Role;
use App\Features\Role as FeaturesRole;
use App\Models\Tenants\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (! hasFeatureAndPermission(FeaturesRole::class)) {
            $user->assignRole(Role::admin);
        }
    }
}
