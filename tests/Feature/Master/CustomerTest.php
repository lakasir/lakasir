<?php

namespace Tests\Feature\Master;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * A basic feature create test.
     *
     * @return void
     */
    public function test_customer_create()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/master/customer', [
            'name' => 'customer a',
            'email' => 'customer@mail.com',
            'point' => rand(1, 30)
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/customer');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_customer_browse()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/master/customer');

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_customer_show()
    {
        $user = User::find(1);
        factory(Customer::class, 10)->create();
        $response = $this->actingAs($user)->get('/master/customer/' . Customer::inRandomOrder()->first()->id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_customer_update()
    {
        $user = User::find(1);
        factory(Customer::class, 10)->create();
        $response = $this->actingAs($user)->put('/master/customer/' . Customer::inRandomOrder()->first()->id, [
            'name' => 'customer b',
            'email' => 'customer@mail.com',
            'point' => rand(1, 30)
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/customer');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_customer_delete()
    {
        $user = User::find(1);
        factory(Customer::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/customer/' . Customer::inRandomOrder()->first()->id);

        $response->assertStatus(302);
    }


    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_customer_bulk_delete()
    {
        $user = User::find(1);
        factory(Customer::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/customer/bulk-destroy', [
            'ids' => Customer::inRandomOrder()->get()->pluck('id')
        ]);

        $response->assertStatus(302);
    }
}
