<?php

namespace Tests\Feature\Master;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature create test.
     *
     * @return void
     */
    public function test_category_create()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/master/category', [
            'name' => 'category'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/category');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_category_browse()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/master/category');

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_category_show()
    {
        $user = User::find(1);
        factory(Category::class, 10)->create();
        $response = $this->actingAs($user)->get('/master/category/' . Category::inRandomOrder()->first()->id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_category_update()
    {
        $user = User::find(1);
        factory(Category::class, 10)->create();
        $response = $this->actingAs($user)->put('/master/category/' . Category::inRandomOrder()->first()->id, [
            'name' => 'siap'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/category');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_category_delete()
    {
        $user = User::find(1);
        factory(Category::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/category/' . Category::inRandomOrder()->first()->id);

        $response->assertStatus(302);
    }


    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_category_bulk_delete()
    {
        $user = User::find(1);
        factory(Category::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/category/bulk-destroy', [
            'ids' => Category::inRandomOrder()->get()->pluck('id')
        ]);

        $response->assertStatus(302);
    }
}
