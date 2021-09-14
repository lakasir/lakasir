<?php

namespace Tests\Feature\Master;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class ItemTest extends FeatureTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_response_unauthorized_for_see_list_items()
    {
        $response = $this->loginAs()
                         ->get(route('item.index'), $this->data())
                         ->assertStatus(403);
        $response->assertSee('This action is unauthorized.');
    }

    /** @test */
    public function it_see_item_list_in_view()
    {
        $this->assignPermission('browse-item');
        $response = $this->loginAs()->get(route('item.index'))->assertStatus(200);
        $response->assertViewIs('app.master.items.index');
    }

    /** @test */
    public function it_see_items_via_ajax_datatables()
    {
        $this->assignPermission('browse-item');
        /** @var \App\Models\Item $item */
        $item = factory(Item::class)->create();
        $response = $this->loginAs()->getJson(route('item.index'), $this->ajaxHeader())->assertStatus(200);
        $response->assertJsonFragment(['category_name' => $item->category->name])
            ->assertSeeText($item->name);
    }

    /** @test */
    public function it_response_unauthorized_for_create_items()
    {
        $response = $this->loginAs()->post(route('item.create'), $this->data())->assertStatus(405);
        $response->assertSee('This action is unauthorized.');
    }

    /** @test */
    public function it_response_back_with_validation_error_after_create_item()
    {
        $this->assignPermission('create-item');
        $response = $this->loginAs()->post(route('item.store'), $this->data())->assertStatus(302);
        $response->assertSessionHasErrors(['initial_price', 'selling_price', 'category_id']);
    }

    private function data(): array
    {
        return [
            'name' => 'product abal abal',
            'stock' => 30,
            'image' => UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10)
        ];
    }
}
