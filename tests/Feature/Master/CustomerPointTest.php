<?php

namespace Tests\Feature\Master;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerPointTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_insert_point(): void
    {
        factory(Customer::class, 10)->create();
        $customer = Customer::inRandomOrder()->limit(1)->first();
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/master/customer-point', [
            'date' => today()->format('Y-m-d'),
            'point' => rand(0, 100),
            'customer_id' => $customer->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/customer');
    }
}
