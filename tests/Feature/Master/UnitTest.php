<?php

namespace Tests\Feature\Master;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTest extends TestCase
{
    /**
     * A basic feature create test.
     *
     * @return void
     */
    public function test_unit_create()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/master/unit', [
            'name' => 'PCS'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/unit');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_unit_browse()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/master/unit');

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_unit_show()
    {
        $user = User::find(1);
        factory(Unit::class, 10)->create();
        $response = $this->actingAs($user)->get('/master/unit/' . Unit::inRandomOrder()->first()->id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_unit_update()
    {
        $user = User::find(1);
        factory(Unit::class, 10)->create();
        $response = $this->actingAs($user)->put('/master/unit/' . Unit::inRandomOrder()->first()->id,[
            'name' => 'siap'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/unit');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_unit_delete()
    {
        $user = User::find(1);
        factory(Unit::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/unit/' . Unit::inRandomOrder()->first()->id);

        $response->assertStatus(302);
    }


    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_unit_bulk_delete()
    {
        $user = User::find(1);
        factory(Unit::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/unit/bulk-destroy', [
            'ids' => Unit::inRandomOrder()->get()->pluck('id')
        ]);

        $response->assertStatus(302);
    }

}
