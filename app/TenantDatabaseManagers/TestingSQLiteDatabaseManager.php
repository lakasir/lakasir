<?php

namespace App\TenantDatabaseManagers;

use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class TestingSQLiteDatabaseManager implements TenantDatabaseManager
{
    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        $dbPath = database_path('tenant_' . str_replace(['lakasir_', '-'], ['', '_'], $tenant->database()->getName()) . '.sqlite');
        
        try {
            // Create empty SQLite file
            if (!file_exists($dbPath)) {
                file_put_contents($dbPath, '');
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        $dbPath = database_path('tenant_' . str_replace(['lakasir_', '-'], ['', '_'], $tenant->database()->getName()) . '.sqlite');
        
        try {
            if (file_exists($dbPath)) {
                return unlink($dbPath);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function databaseExists(string $name): bool
    {
        $dbPath = database_path('tenant_' . str_replace(['lakasir_', '-'], ['', '_'], $name) . '.sqlite');
        return file_exists($dbPath);
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        // For testing, create separate SQLite files for each tenant to avoid conflicts
        $testDbPath = database_path('tenant_' . str_replace(['lakasir_', '-'], ['', '_'], $databaseName) . '.sqlite');
        $baseConfig['database'] = $testDbPath;
        
        return $baseConfig;
    }

    public function setConnection(string $connection): void
    {
        // Nothing to do for SQLite testing
    }
}