<?php

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
            ->assertSeeLivewire('pages.auth.register');
    });

    it('user can create the tenant account through web', function () {
        Notification::fake();
        $testEmail = 'testweb@mail.com';

        livewire('pages.auth.register')
            ->set('shopName', 'test')
            ->set('email', $testEmail)
            ->set('password', 'password')
            ->set('passwordConfirmation', 'password')
            ->set('domain', 'tokotestweb')
            ->set('agreeTerms', true)
            ->call('create')
            ->assertHasNoErrors()
            ->assertStatus(200);

        $tenant = Tenant::find('tokotestweb');
        Notification::assertSentTo(
            [$tenant->user], DomainCreated::class
        );

        $this->assertDatabaseHas('tenants', [
            'id' => 'tokotestweb',
        ]);
        $this->assertDatabaseHas('tenant_users', [
            'email' => $testEmail,
        ]);
        $tenant->run(function () use ($testEmail) {
            $this->assertDatabaseHas('users', [
                'email' => $testEmail,
            ]);
        });
    });
})->skip('not implemented yet');
afterAll(function () {
    DB::statement('DROP DATABASE IF EXISTS lakasir_tokotestweb');
});
