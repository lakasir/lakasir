<?php

namespace Tests\Feature\Api\Transaction;

use App\Models\Item;
use App\Models\PaymentMethod;
use App\Traits\SetClietnCredentials;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class SellingTest extends TestCase
{
    use SetClietnCredentials;

    public function test_selling_list_item_success($search = null): void
    {
        $this->setClientCredentialsToken();

        $response = $this->get(route('api.selling.index', [
            'search' => $search
        ]), $this->oauth_headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'payload' => [
                    ['id', 'name', 'image', 'stock', 'selling_price']
                ]
            ]);
    }

    public function test_error_422_selling_items(): void
    {
        $this->setClientCredentialsToken();

        $response = $this->postJson(route('api.selling.store'), array_merge($this->data(), [
            'money' => ''
        ]), $this->oauth_headers);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_create_selling_items(): void
    {
        $this->setClientCredentialsToken();

        $data = $this->data();
        $response = $this->postJson(route('api.selling.store'), $data, $this->oauth_headers);

        $response->assertStatus(JsonResponse::HTTP_OK);
    }


    protected function data($money = 8000): array
    {
        $paymentMethod = PaymentMethod::inRandomOrder()->take(1)->first();
        $money = 0;
        $items = Item::inRandomOrder()->take(rand(1, 10))->get()->map(function($el) use(&$money) {
            if ($el->last_price) {
                $stock = 2;
                $money = $money + ($el->last_price->selling_price * $stock);
                if ($stock != 0) {
                    $qty = rand(1, 2);
                    return [
                        'id' => $el->id,
                        'qty' => $qty
                    ];
                }
            }
        })->toArray();

        return [
            'payment_method_id' => $paymentMethod->id,
            'money' => $money,
            'items' => $items,
        ];
    }
}
