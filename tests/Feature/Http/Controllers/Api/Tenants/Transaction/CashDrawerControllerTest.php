<?php

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Cache;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('cash_drawer_enabled', true);
    Setting::set('selling_method', 'fifo');
    Cache::clear();
    $this->user = User::first();
    $this->product = Product::factory()->create([
        'initial_price' => 10000,
        'selling_price' => 20000,
        'stock' => 50,
    ]);
    $this->cash = PaymentMethod::where('name', 'Cash')->first();
    $this->qris = PaymentMethod::create(['name' => 'QRIS', 'is_cash' => false, 'is_wallet' => true]);
});

function sell(object $test, PaymentMethod $method, int $qty, float $payedMoney): void
{
    actingAs($test->user)->postJson('/api/transaction/selling', [
        'payed_money' => $payedMoney,
        'friend_price' => false,
        'payment_method_id' => $method->id,
        'products' => [['product_id' => $test->product->id, 'qty' => $qty]],
    ])->assertOk();
}

test('opening a shift while one is already open is rejected instead of overwriting its cash', function () {
    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 100000])->assertOk();

    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 5000])
        ->assertStatus(409);

    expect(CashDrawer::lastOpened()->first()->cash)->toEqual(100000);
});

test('the shift summary reports expected cash and sales per payment method', function () {
    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 100000])->assertOk();

    sell($this, $this->cash, 1, 50000);  // receives 20.000 cash, gives 30.000 change
    sell($this, $this->cash, 2, 40000);  // receives 40.000 cash
    sell($this, $this->qris, 1, 20000);  // 20.000 via QRIS

    $response = actingAs($this->user)->getJson('/api/transaction/cash-drawer/summary')->assertOk();

    $response->assertJsonPath('data.opening_cash', 100000)
        ->assertJsonPath('data.cash_sales', 60000)
        ->assertJsonPath('data.expected_cash', 160000)
        ->assertJsonPath('data.counted_cash', null)
        ->assertJsonPath('data.difference', null)
        ->assertJsonPath('data.sales_count', 3)
        ->assertJsonPath('data.sales_total', 80000);

    $methods = collect($response->json('data.payment_methods'))->keyBy('name');
    expect($methods['Cash']['count'])->toBe(2)
        ->and($methods['Cash']['total'])->toEqual(60000)
        ->and($methods['QRIS']['count'])->toBe(1)
        ->and($methods['QRIS']['total'])->toEqual(20000);
});

test('closing a shift stores the counted cash and the summary shows the difference', function () {
    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 100000])->assertOk();
    sell($this, $this->cash, 1, 20000);
    $drawerId = CashDrawer::lastOpened()->first()->id;

    actingAs($this->user)->postJson('/api/transaction/cash-drawer/close', ['counted_cash' => 118000])
        ->assertOk();

    $drawer = CashDrawer::find($drawerId);
    expect($drawer->counted_cash)->toEqual(118000)
        ->and($drawer->closed_by)->toBe($this->user->id)
        ->and($drawer->closed_at)->not->toBeNull();

    actingAs($this->user)->getJson("/api/transaction/cash-drawer/summary?id={$drawerId}")
        ->assertOk()
        ->assertJsonPath('data.expected_cash', 120000)
        ->assertJsonPath('data.counted_cash', 118000)
        ->assertJsonPath('data.difference', -2000);
});

test('sales from an earlier shift are not counted in the current one', function () {
    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 0])->assertOk();
    sell($this, $this->cash, 1, 20000);
    actingAs($this->user)->postJson('/api/transaction/cash-drawer/close', ['counted_cash' => 20000])->assertOk();

    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 50000])->assertOk();

    actingAs($this->user)->getJson('/api/transaction/cash-drawer/summary')
        ->assertOk()
        ->assertJsonPath('data.sales_count', 0)
        ->assertJsonPath('data.expected_cash', 50000);
});

test('the summary is 404 when no shift is open', function () {
    actingAs($this->user)->getJson('/api/transaction/cash-drawer/summary')->assertNotFound();
});

test('counted cash must be a positive number', function () {
    actingAs($this->user)->postJson('/api/transaction/cash-drawer', ['cash' => 0])->assertOk();

    actingAs($this->user)->postJson('/api/transaction/cash-drawer/close', ['counted_cash' => -1])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('counted_cash');
});
