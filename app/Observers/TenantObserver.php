<?php

namespace App\Observers;

use App\Tenant;

class TenantObserver
{
    public function created(Tenant $tenant): void
    {
    }
}
