<?php

use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabaseWithTenant::class);

it('renders phase 2 menu home and settings pages for authenticated users', function (string $uri) {
    $user = User::query()->first();

    actingAs($user, 'web');

    get($uri)->assertOk();
})->with([
    '/app/dashboard',
    '/app/settings',
    '/app/settings/category',
    '/app/settings/users',
    '/app/settings/roles',
    '/app/settings/printer',
    '/app/settings/about',
    '/app/settings/profile',
]);

it('shows landing settings entries from phase 2 figma', function () {
    $user = User::query()->first();

    actingAs($user, 'web');

    get('/app/settings')
        ->assertOk()
        ->assertSeeText('Category')
        ->assertSeeText('Currency')
        ->assertSeeText('Language')
        ->assertSeeText('Display');
});
