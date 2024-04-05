<?php

namespace App\Services;

use App\Notifications\DomainCreated;
use App\Tenant;
use Illuminate\Support\Facades\Artisan;

class RegisterTenant
{
    public function create(array $data): Tenant
    {
        $name = $data['name'] ?? null;
        /** @var Tenant */
        $tenant = Tenant::create([
            'id' => $name,
            'tenancy_db_name' => 'lakasir_'.$name,
        ]);
        $tenant->domains()->create([
            'domain' => $data['domain'],
        ]);
        $tenant->user()->create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $tenant->user->about()->create([
            'shop_name' => $data['full_name'] ?? null,
            'business_type' => $data['business_type'],
        ]);

        $tenant->user->notify(new DomainCreated());

        Artisan::call('tenants:seed', [
            '--tenants' => [$tenant->id],
            '--force' => true,
        ]);

        return $tenant;
    }
}
