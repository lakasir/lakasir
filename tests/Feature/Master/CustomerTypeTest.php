<?php

namespace Tests\Feature\Master;

use App\Models\CustomerPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase as TestCase;

class CustomerTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_browse_categories(): void
    {
        $this->loginAs()
            ->get(route('category.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_browse_categories(): void
    {
        $this->assignPermission('browse-category');
        $this->loginAs()
            ->get(route('category.index'))
            ->assertStatus(200)
            ->assertViewIs('app.master.categories.index')
            ->assertSeeText(__('app.categories.title'));
    }

    /** @test */
    public function it_can_browse_categories_via_ajax_datatbales(): void
    {
        $this->assignPermission('browse-category');
        $category = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->getJson(route('category.index'), $this->ajaxHeader())
            ->assertJsonFragment([
                'name' => $category->name,
            ])
            ->assertSeeText($category->name)
            ->assertSeeText($category->id);
    }

    /** @test */
    public function it_cant_see_create_form_category(): void
    {
        $this->loginAs()
            ->get(route('category.create'))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_create_form_category(): void
    {
        $this->assignPermission('create-category');
        $this->loginAs()
            ->get(route('category.create'))
            ->assertSeeText(__('app.categories.create.title'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_store_category_validation_error(): void
    {
        $this->assignPermission('create-category');
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->post(route('category.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);
    }

    /** @test */
    public function it_can_store_category(): void
    {
        $this->assignPermission('create-category');
        $request = $this->data();
        $this->loginAs()
            ->post(route('category.store'), $request)
            ->assertStatus(302);
        $category_created = CustomerPoint::where('name', $request['name'])->first();
        $this->assertTrue(!is_null($category_created));
        $this->assertFlashLevel('success', __('app.global.message.success.create', [
            'item' => ucfirst('category')
        ]));
    }

    /** @test */
    public function it_cant_see_edit_form_category(): void
    {
        $category = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->get(route('category.edit', $category))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_edit_form_category(): void
    {
        $this->assignPermission('update-category');
        $category = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->get(route('category.edit', $category))
            ->assertSeeText(__('app.categories.edit.title'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_update_category_validation_error(): void
    {
        $this->assignPermission('update-category');
        $category = factory(CustomerPoint::class)->create();
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->patch(route('category.update', $category), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);
    }

    /** @test */
    public function it_can_update_category(): void
    {
        $this->assignPermission('update-category');
        $category = factory(CustomerPoint::class)->create();
        $data = $this->data();
        $request = array_merge($data, ['name' => 'siap']);
        $this->loginAs()
            ->patch(route('category.update', $category), $request)
            ->assertStatus(302);
        $category_original = CustomerPoint::where('name', $data['name'])->first();
        $category_updated = CustomerPoint::where('name', $request['name'])->first();
        $this->assertTrue(is_null($category_original));
        $this->assertTrue(!is_null($category_updated));
        $this->assertFlashLevel('success', __('app.global.message.success.update', [
            'item' => ucfirst('category')
        ]));
    }

    /** @test */
    public function it_cant_see_delete_category(): void
    {
        $category = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->delete(route('category.destroy', $category))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_category(): void
    {
        $this->assignPermission('delete-category');
        $category = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->delete(route('category.destroy', $category))
            ->assertStatus(302);
        $category_deleted = CustomerPoint::find($category->id);
        $this->assertTrue(is_null($category_deleted));
        $this->assertFlashLevel('success', __('app.global.message.success.delete', [
            'item' => ucfirst('category')
        ]));
    }

    /** @test */
    public function it_cant_see_bulk_delete_category(): void
    {
        $ids = [
            'ids' => []
        ];
        $this->loginAs()
            ->delete(route('category.bulkDestroy'), $ids)
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_cant_bulk_delete_category_validation_error(): void
    {
        $this->assignPermission('bulk-delete-category');
        $ids = [
            'ids' => null
        ];
        $this->loginAs()
            ->delete(route('category.bulkDestroy'), $ids)
            ->assertSessionHasErrors('ids')
            ->assertStatus(302);
    }

    /** @test */
    public function it_can_bulk_delete_category(): void
    {
        $this->assignPermission('bulk-delete-category');
        $categories = factory(CustomerPoint::class, 2)->create();
        $id = $categories->pluck('id');
        $ids = [
            'ids' => $id->toArray()
        ];
        $this->loginAs()
            ->delete(route('category.bulkDestroy'), $ids)
            ->assertStatus(302);
        $category_deleted = CustomerPoint::find($id);
        $this->assertTrue($category_deleted->isEmpty());
        $this->assertFlashLevel('success', __('app.global.message.success.bulk-delete', [
            'item' => ucfirst('category')
        ]));
    }

    private function data(): array
    {
        return [
            'name' => $this->faker->randomLetter
        ];
    }
}

