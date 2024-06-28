<?php

namespace App\Observers;

use App\Models\Tenants\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
    }
}
