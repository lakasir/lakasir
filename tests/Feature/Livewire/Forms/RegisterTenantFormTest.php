<?php

use App\Livewire\Forms\RegisterTenantForm;
use App\Notifications\DomainCreated;
use App\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{get};
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

describe('Register Test', function () {
    beforeEach(function () {
        config(['tenancy.central_domains' => ['localhost.com']]);
        DB::statement('DROP DATABASE IF EXISTS lakasir_tokotest');
    });
    it('user can see the register page', function () {
        get('/auth/register')
            ->assertSeeLivewire(RegisterTenantForm::class);
    });

    it('user can create the tenant account', function () {
        Notification::fake();
        $data = [
            'full_name' => 'test',
            'email' => 'test@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'shop_name' => 'test',
            'business_type' => 'fnb',
            'domain' => 'tokotest',
        ];

        livewire(RegisterTenantForm::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertStatus(200);

        Notification::assertSentTo(
            [Tenant::first()->user], DomainCreated::class
        );

        $this->assertDatabaseHas('tenants', [
            'id' => 'tokotest',
        ]);
        $this->assertDatabaseHas('tenant_users', [
            'email' => 'test@mail.com',
        ]);
        Tenant::first()->run(function () {
            $this->assertDatabaseHas('users', [
                'email' => 'test@mail.com',
            ]);
        });
    });
});
afterAll(function () {
    DB::statement('DROP DATABASE IF EXISTS lakasir_tokotest');
});
