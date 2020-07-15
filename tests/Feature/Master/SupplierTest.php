<?php

namespace Tests\Feature\Master;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    /**
     * A basic feature create test.
     *
     * @return void
     */
    public function test_supplier_create()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/master/supplier', [
            'name' => 'supplier a',
            'shop_name' => 'Toko ABC',
            'name' => 'robikin',
            'phone' => '089638706830',
            'address' => 'Indonesia',
            'code' => 'ABCD1234567'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/supplier');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_supplier_browse()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/master/supplier');

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_supplier_show()
    {
        $user = User::find(1);
        factory(Supplier::class, 10)->create();
        $response = $this->actingAs($user)->get('/master/supplier/' . Supplier::inRandomOrder()->first()->id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_supplier_update()
    {
        $user = User::find(1);
        factory(Supplier::class, 10)->create();
        $response = $this->actingAs($user)->put('/master/supplier/' . Supplier::inRandomOrder()->first()->id,[
            'name' => 'supplier a',
            'shop_name' => 'Toko ABC',
            'name' => 'robikin',
            'phone' => '089638706830',
            'address' => 'Indonesia',
            'code' => 'ABCD1234567'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/supplier');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_supplier_delete()
    {
        $user = User::find(1);
        factory(Supplier::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/supplier/' . Supplier::inRandomOrder()->first()->id);

        $response->assertStatus(302);
    }


    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_supplier_bulk_delete()
    {
        $user = User::find(1);
        factory(Supplier::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/supplier/bulk-destroy', [
            'ids' => Supplier::inRandomOrder()->get()->pluck('id')
        ]);

        $response->assertStatus(302);
    }

}
