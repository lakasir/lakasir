<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Auth\Access\Response;

class CashDrawerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return hasCashDrawerFeature();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashDrawer $cashDrawer): bool
    {
        return hasCashDrawerFeature();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return hasCashDrawerFeature();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashDrawer $cashDrawer): bool
    {
        return hasCashDrawerFeature();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashDrawer $cashDrawer): bool
    {
        return hasCashDrawerFeature();
    }
}
