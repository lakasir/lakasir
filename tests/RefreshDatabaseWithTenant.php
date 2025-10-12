<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

trait RefreshDatabaseWithTenant
{
    use RefreshDatabase {
        beginDatabaseTransaction as parentBeginDatabaseTransaction;
    }

    protected $mockTenant;

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
        $this->mockTenant = mockTenant();

        tenancy()->initialize($this->mockTenant);

        URL::forceRootUrl("http://{$this->mockTenant->domains[0]->domain}");
    }

    public function mockFilamentUser($user, $isLoggedIn = true)
    {
        // Set the current panel using the real instance before mocking
        $filamentManager = app(\Filament\FilamentManager::class);
        $panel = $filamentManager->getPanel('tenant');
        $filamentManager->setCurrentPanel($panel);

        // Mock Filament auth properly
        $guardMock = \Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
        $guardMock->shouldReceive('user')->andReturn($user);
        $guardMock->shouldReceive('id')->andReturn($user->id);
        $guardMock->shouldReceive('check')->andReturn($isLoggedIn);

        \Filament\Facades\Filament::shouldReceive('auth')->andReturn($guardMock);
        \Filament\Facades\Filament::shouldReceive('hasBreadcrumbs')->andReturn(false);
        \Filament\Facades\Filament::shouldReceive('hasUnsavedChangesAlerts')->andReturn(false);
        \Filament\Facades\Filament::shouldReceive('arePasswordsRevealable')->andReturn(true);
        \Filament\Facades\Filament::shouldReceive('hasRegistration')->andReturn(false);
        \Filament\Facades\Filament::shouldReceive('hasPasswordReset')->andReturn(false);
        \Filament\Facades\Filament::shouldReceive('getWidgets')->andReturn([]);
        \Filament\Facades\Filament::shouldReceive('getUrl')->andReturn('/');
        \Filament\Facades\Filament::shouldReceive('getBrandName')->andReturn('Test');
        \Filament\Facades\Filament::shouldReceive('getBrandLogo')->andReturn(null);
        \Filament\Facades\Filament::shouldReceive('getDarkModeBrandLogo')->andReturn(null);
        \Filament\Facades\Filament::shouldReceive('getBrandLogoHeight')->andReturn(null);
        \Filament\Facades\Filament::shouldReceive('getCurrentPanel')->andReturn($panel);
        \Filament\Facades\Filament::shouldReceive('getTenant')->andReturn($this->mockTenant);
        \Filament\Facades\Filament::shouldReceive('getPanel')->withAnyArgs()->andReturn($panel);
    }
}
