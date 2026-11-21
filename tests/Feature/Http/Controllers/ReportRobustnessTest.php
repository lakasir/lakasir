<?php

use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('a report without dates covers today instead of crashing', function (string $url) {
    $owner = User::first();
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    actingAs($owner)->get($url)->assertOk()->assertHeader('content-type', 'application/pdf');
})->with([
    '/member/selling-report/generate',
    '/member/product-report/generate',
    '/member/cashier-report/generate',
    '/member/purchasing-report/generate',
]);

test('an unknown notification is a 404, not a 500', function () {
    $product = Product::factory()->create();

    actingAs(User::first())
        ->putJson("/api/notification/not-a-real-id/{$product->id}")
        ->assertNotFound();
});

test('a notification of another user is a 404', function () {
    $product = Product::factory()->create();
    $other = User::factory()->create();
    $owner = User::first();

    $other->notify(new \App\Notifications\StockRunsOut([['id' => $product->id, 'name' => $product->name, 'stock' => 1]]));

    actingAs($owner)
        ->putJson("/api/notification/{$other->notifications()->first()->id}/{$product->id}")
        ->assertNotFound();
});
