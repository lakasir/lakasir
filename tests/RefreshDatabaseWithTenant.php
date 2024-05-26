<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

trait RefreshDatabaseWithTenant
{
    use RefreshDatabase {
        beginDatabaseTransaction as parentBeginDatabaseTransaction;
    }

    /**
     * We need to hook initialize tenancy _before_ we start the database
     * transaction, otherwise it cannot find the tenant connection.
     */
    public function beginDatabaseTransaction()
    {
        $this->initializeTenant();

        $this->parentBeginDatabaseTransaction();
    }

    public function initializeTenant()
    {
        $tenant = mockTenant();

        tenancy()->initialize($tenant);

        URL::forceRootUrl("http://{$tenant->domains[0]->domain}");
    }
}
