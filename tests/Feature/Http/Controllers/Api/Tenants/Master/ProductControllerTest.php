<?php

use App\Models\Tenants\User;
use Illuminate\Http\Response;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test("can'\t create product", function () {
    $user = User::first();
    actingAs($user)->postJson('/api/master/product', [])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});
