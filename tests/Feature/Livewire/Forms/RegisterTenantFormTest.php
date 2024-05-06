<?php

use App\Livewire\Forms\Auth\RegisterTenantForm;
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
        DB::statement('DROP DATABASE IF EXISTS lakasir_tokotestweb');
    });
    it('user can see the register page', function () {
        get('/auth/register')
            ->assertSeeLivewire(RegisterTenantForm::class);
    });

    it('user can create the tenant account through web', function () {
        Notification::fake();
        $data = [
            'full_name' => 'test',
            'email' => 'testweb@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'shop_name' => 'test',
            'business_type' => 'fnb',
            'domain' => 'tokotestweb',
        ];

        livewire(RegisterTenantForm::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertStatus(200);

        $tenant = Tenant::find('tokotestweb');
        Notification::assertSentTo(
            [$tenant->user], DomainCreated::class
        );

        $this->assertDatabaseHas('tenants', [
            'id' => 'tokotestweb',
        ]);
        $this->assertDatabaseHas('tenant_users', [
            'email' => 'testweb@mail.com',
        ]);
        $tenant->run(function () {
            $this->assertDatabaseHas('users', [
                'email' => 'testweb@mail.com',
            ]);
        });
    });
})->skip('not implemented yet');
afterAll(function () {
    DB::statement('DROP DATABASE IF EXISTS lakasir_tokotestweb');
});
