<?php

namespace Tests;

use App\Models\Tenants\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

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
        $this->initializeApp();

        $this->parentBeginDatabaseTransaction();
    }

    public function initializeApp()
    {
        User::factory()->createQuietly();
        Artisan::call('db:seed', [
            '--class' => 'PermissionSeeder',
        ]);
        Artisan::call('db:seed', [
            '--class' => 'PaymentMethodSeeder',
        ]);
        Artisan::call('db:seed', [
            '--class' => 'CategorySeeder',
        ]);
    }
}
