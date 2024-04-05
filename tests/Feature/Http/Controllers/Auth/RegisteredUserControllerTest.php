<?php

use App\Notifications\DomainCreated;
use App\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

describe('Registered User Controller', function () {
    beforeEach(function () {
        config(['tenancy.central_domains' => ['localhost.com']]);
        DB::statement('DROP DATABASE IF EXISTS lakasir_tokotest');
    });
    it('user can create the tenant account', function () {
        Notification::fake();

        $data = [
            'domain' => 'tokotest.localhost.com',
            'email' => 'test@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'business_type' => 'fnb',
        ];
        $response = postJson('/api/domain/register', $data);

        $response->assertStatus(200);

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

    it('user cannot create the tenant business_type not in list', function () {
        $data = [
            'domain' => 'tokotest.localhost.com',
            'business_type' => 'test',
        ];
        $response = postJson('/api/domain/register', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['business_type']);
    });

    it('user can not create the tenant account with invalid domain', function () {
        $data = [
            'name' => 'test',
            'domain' => 'test.localhost',
        ];
        $response = postJson('/api/domain/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['domain']);
    });

    it('user can not create the tenant with invalid busines type', function () {
        $data = [
            'domain' => 'test.localhost.com',
            'business_type' => 'test',
        ];
        $response = postJson('/api/domain/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['business_type']);
    });
});
afterAll(function () {
    DB::statement('DROP DATABASE IF EXISTS lakasir_tokotest');
});
