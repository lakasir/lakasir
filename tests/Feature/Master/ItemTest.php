<?php

namespace Tests\Feature\Master;

use App\Models\Category;
use App\Models\Item;
use App\Models\Media;
use App\Models\Price;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function test_error_item_create(): void
    {
        factory(Unit::class, 20)->create();
        factory(Category::class, 20)->create();
        $user = User::find(1);
        $response = $this->actingAs($user)->post(route('item.store'), [
            'name' => 'product abal abal',
            'stock' => 30,
            'unit_id' => '',
            'image' => UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10)
        ]);
        $media = Media::latest()->first();

        $response->assertStatus(302);
        Storage::disk('local')->assertExists($media->fullFileName);
        $response->assertRedirect('');
    }

    /**
     * A basic feature create test.
     *
     * @return void
     */
    public function test_item_create()
    {
        factory(Unit::class, 20)->create();
        factory(Category::class, 20)->create();
        $user = User::find(1);
        $response = $this->actingAs($user)->post(route('item.store'), [
            'name' => 'product abal abal',
            'stock' => 30,
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'initial_price' => 20000,
            'selling_price' => 25000,
            'image' => UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10)
        ]);
        $media = Media::latest()->first();


        $response->assertStatus(302);
        Storage::disk('local')->assertExists($media->fullFileName);
        $response->assertRedirect('/master/item');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_item_browse()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('item.index'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_item_show()
    {
        $user = User::find(1);
        factory(Item::class, 10)->create();
        $response = $this->actingAs($user)->get(route('item.show', Item::inRandomOrder()->first()));

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_error_item_update()
    {
        $item = Item::inRandomOrder()->first();
        $item->createMediaFromFile(UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10));
        $user = User::find(1);
        $response = $this->actingAs($user)->put(route('item.update', $item), [
        ]);


        $response->assertStatus(302);
        $response->assertRedirect('');
    }
    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_item_update()
    {
        $item = Item::inRandomOrder()->first();
        $item->createMediaFromFile(UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10));
        $user = User::find(1);
        $response = $this->actingAs($user)->put(route('item.update', $item), [
            'name' => 'product sangat abal abal',
            'stock' => 30,
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'image' => UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10)
        ]);
        $media = Media::latest()->first();

        Storage::disk('local')->assertExists($media->fullFileName);

        $response->assertStatus(302);
        $response->assertRedirect('/master/item');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_item_delete()
    {
        $user = User::find(1);
        factory(Item::class, 10)->create();
        $response = $this->actingAs($user)->delete(route('item.destroy', Item::inRandomOrder()->first()));

        $response->assertStatus(302);
    }


    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_item_bulk_delete()
    {
        $user = User::find(1);
        factory(Item::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/item/bulk-destroy', [
            'ids' => Item::inRandomOrder()->get()->pluck('id')
        ]);

        $response->assertStatus(302);
    }
}
