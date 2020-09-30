<?php

namespace Tests\Feature\Api\Transaction;

use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Selling;
use App\Models\SellingDetail;
use App\Repositories\Item as ItemRepository;
use App\Traits\SetClietnCredentials;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SellingTest extends TestCase
{
    use SetClietnCredentials;

    /**
     * @var int
     */
    private $money;

    /**
     * @var Item
     */
    /* private $item; */

    /**
     * @param Item $item
     */
    /* public function __construct() */
    /* { */
    /*     $this->item = new ItemRepository(); */
    /* } */


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
        $itemRepository = new ItemRepository();
        $this->setClientCredentialsToken();

        $money = 10000;
        $data = $this->data($money);
        $items = Arr::get($data, 'items');
        $paymentMethodId = Arr::get($data, 'payment_method_id');
        $totalQty = array_sum(Arr::pluck($items, 'qty'));
        $item_id = Arr::pluck($items, 'id');

        $totalSellingPrice = $itemRepository->totalPriceByRequest($items);
        $totalInitialPrice = $itemRepository->totalPriceByRequest($items, 'initial_price');

        $response = $this->postJson(route('api.selling.store'), $data, $this->oauth_headers);

        $response->assertStatus(JsonResponse::HTTP_OK);

        $this->assertDatabaseHas('sellings', [
            'payment_method_id' => $paymentMethodId,
            'user_id' =>  $this->user->id,
            'total_qty' => $totalQty,
            'total_price' => $totalSellingPrice,
            'total_profit' => $totalSellingPrice - $totalInitialPrice,
            'money' => $this->money + $money,
            'refund' => ($this->money + $money) - $totalSellingPrice,
        ]);

        $this->assertDatabaseHas('selling_details', [
            'item_id' => $item_id
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Selling::truncate();
        SellingDetail::truncate();
    }

    public function test_create_selling_items_with_customer(): void
    {
        $itemRepository = new ItemRepository();
        $this->setClientCredentialsToken();

        $customer = Customer::has('customerType')->inRandomOrder()->first();
        $money = 10000;
        $data = array_merge($this->data($money), ['customer_id' => $customer->id]);
        $items = Arr::get($data, 'items');
        $paymentMethodId = Arr::get($data, 'payment_method_id');
        $totalQty = array_sum(Arr::pluck($items, 'qty'));
        $item_id = Arr::pluck($items, 'id');
        /* $point = $customer->customerType->default_point +  $customer->points->sum('point'); */

        $totalSellingPrice = $itemRepository->totalPriceByRequest($items);
        $totalInitialPrice = $itemRepository->totalPriceByRequest($items, 'initial_price');

        $response = $this->postJson(route('api.selling.store'), $data, $this->oauth_headers);

        $response->assertStatus(JsonResponse::HTTP_OK);

        $this->assertDatabaseHas('sellings', [
            'payment_method_id' => $paymentMethodId,
            'customer_id' => $customer->id,
            'user_id' =>  $this->user->id,
            'total_qty' => $totalQty,
            'total_price' => $totalSellingPrice,
            'total_profit' => $totalSellingPrice - $totalInitialPrice,
            'money' => $this->money + $money,
            'refund' => ($this->money + $money) - $totalSellingPrice,
        ]);

        $this->assertDatabaseHas('selling_details', [
            'item_id' => $item_id
        ]);

        $this->assertDatabaseHas('customer_points', [
            'customer_id' => $customer->id,
            'point' => $customer->customerType->default_point
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Selling::truncate();
        SellingDetail::truncate();
        CustomerPoint::truncate();
    }

    public function test_list_selling_activity(string $search = null): void
    {
        $this->setClientCredentialsToken();

        $response = $this->get(route('api.selling.activity', [
            'search' => $search
        ]), $this->oauth_headers);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success']);
    }



    private function data($money = 8000): array
    {
        $paymentMethod = PaymentMethod::inRandomOrder()->where('visible_in->purchasing', true)->first();
        $items = $this->items();

        return [
            'payment_method_id' => $paymentMethod->id,
            'money' => $this->money + $money,
            'items' => $items,
        ];
    }

    private function items(): array
    {
        $money = 0;
        $items = Item::inRandomOrder()->has('log_stocks')->take(3)->get();
        $items = $items->map(function($el) use(&$money) {
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

        $this->money = $money;

        return $items;
    }
}
