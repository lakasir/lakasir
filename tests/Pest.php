<?php

use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\User;
use App\Models\Tenants\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'sanctum']);

        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_owner' => true,
        ])->assignRole('admin');

        PaymentMethod::create([
            'name' => 'Cash',
            'is_credit' => false,
        ]);

        Cache::clear();
    })
    ->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
