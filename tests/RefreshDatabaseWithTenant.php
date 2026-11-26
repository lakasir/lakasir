<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Pennant\FeatureManager;

/**
 * One database now. Every test gets a company in context, and one owner user
 * attached to it, because the global scope, the company_id column and the
 * permission checks all depend on there being a shop.
 */
trait RefreshDatabaseWithTenant
{
    use RefreshDatabase;

    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        // Pennant resolves each flag once per process, and a flag one test
        // turns on would otherwise stay on for the next one.
        app(FeatureManager::class)->flushCache();

        mockTenant();

        return $uses;
    }
}
