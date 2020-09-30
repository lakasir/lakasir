<?php

namespace Tests\Feature\Transaction;

use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Price;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class PurchasingTest extends TestCase
{
    public function test_success_create_purchasing(): void
    {
        $user = User::find(1);
        $data = $this->data([
            'initial_price' => rand(7000, 10000),
            'selling_price' => rand(9000, 10000)
        ]);
        $response = $this->actingAs($user)->post(route('purchasing.store'), $data);

        $paymentMethodId = Arr::get($data, 'payment_method');
        $items = Arr::get($data, 'items');
        $totalQty = array_sum(Arr::pluck($items, 'qty'));

        $response->assertStatus(302);
        $response->assertRedirect(route('purchasing.index'));

        $this->assertDatabaseHas('purchasings', [
            'payment_method_id' => $paymentMethodId,
            'user_id' => $user->id,
            'total_qty' => $totalQty
        ]);
    }

    public function test_error_price_items(): void
    {
        $user = User::find(1);
        $data = $this->data();
        $this->actingAs($user)->get(route('purchasing.create'));

        $response = $this->actingAs($user)->post(route('purchasing.store'), $data);

        $response->assertRedirect(route('purchasing.create'));
    }


    private function data(array $mergeItem = [])
    {
        $items = $this->items();
        if (count($mergeItem) > 0) {
            for ($i = 0; $i < count($items); $i++) {
                $items[$i] = array_merge($items[$i], $mergeItem);
            }
        }

        $paymentMethod = PaymentMethod::inRandomOrder()->where('visible_in->purchasing', true)->first();
        $supplier = Supplier::inRandomOrder()->limit(1)->first();
        return [
            'supplier_id' => $supplier->id,
            'payment_method' => $paymentMethod->id,
            'items' => $items
        ];
    }

    private function items(string $key = null, string $cond = null): array
    {
        $items = Item::inRandomOrder()->doesnthave('log_stocks')->take(3)->get();

        $result = $items->map(function ($item)
        {
            return [
                'item_id' => $item->id,
                'qty' => 20,
            ];
        })->toArray();

        return $result;
    }

}
