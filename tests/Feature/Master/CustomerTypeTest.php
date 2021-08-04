<?php

namespace Tests\Feature\Master;

use App\Models\CustomerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase as TestCase;

class CustomerTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_browse_customer_types(): void
    {
        $this->loginAs()
            ->get(route('customer_type.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_browse_customer_types(): void
    {
        $this->assignPermission('browse-customer_type');
        $this->loginAs()
            ->get(route('customer_type.index'))
            ->assertStatus(200)
            ->assertViewIs('app.master.customer_types.index')
            ->assertSeeText(__('app.customer_types.title'));
    }

    /** @test */
    public function it_can_browse_customer_types_datatables(): void
    {
        $this->assignPermission('browse-customer_type');
        $customer_type = factory(CustomerType::class)->create();
        $this->loginAs()
            ->getJson(route('customer_type.index'), $this->ajaxHeader())
            ->assertJsonFragment([
                'name' => $customer_type->name,
            ])
            ->assertSeeText($customer_type->name)
            ->assertSeeText($customer_type->default_point)
            ->assertSeeText($customer_type->id);
    }

    /** @test */
    public function it_cant_see_create_form_customer_type(): void
    {
        $this->loginAs()
            ->get(route('customer_type.create'))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_create_form_customer_type(): void
    {
        $this->assignPermission('create-customer_type');
        $this->loginAs()
            ->get(route('customer_type.create'))
            ->assertSeeText(__('app.customer_types.create.title'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_store_customer_type_validation_error(): void
    {
        $this->assignPermission('create-customer_type');
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->post(route('customer_type.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);

        $request = array_merge($this->data(), ['default_point' => '']);
        $this->loginAs()
            ->post(route('customer_type.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['default_point' => trans('validation.required', ['attribute' => 'default point'])]);
    }

    /** @test */
    public function it_can_store_customer_type(): void
    {
        $this->assignPermission('create-customer_type');
        $request = $this->data();

        $this->loginAs()
            ->post(route('customer_type.store'), $request)
            ->assertStatus(302);

        $customer_type_created = CustomerType::where('name', $request['name'])->first();

        $this->assertTrue(!is_null($customer_type_created));
        $this->assertFlashLevel('success', __('app.global.message.success.create', [
            'item' => ucfirst('customer_type')
        ]));
    }

    /** @test */
    public function it_cant_see_edit_form_customer_type(): void
    {
        $customer_type = factory(CustomerType::class)->create();
        $this->loginAs()
            ->get(route('customer_type.edit', $customer_type))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_edit_form_customer_type(): void
    {
        $this->assignPermission('update-customer_type');
        $customer_type = factory(CustomerType::class)->create();
        $this->loginAs()
            ->get(route('customer_type.edit', $customer_type))
            ->assertSeeText(__('app.customer_types.edit.title'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_update_customer_type_validation_error(): void
    {
        $this->assignPermission('update-customer_type');
        $customer_type = factory(CustomerType::class)->create();
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->patch(route('customer_type.update', $customer_type), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);

        $request = array_merge($this->data(), ['default_point' => '']);
        $this->loginAs()
            ->put(route('customer_type.update', $customer_type), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['default_point' => trans('validation.required', ['attribute' => 'default point'])]);
    }

    /** @test */
    public function it_can_update_customer_type(): void
    {
        $this->assignPermission('update-customer_type');
        $customer_type = factory(CustomerType::class)->create();
        $data = $this->data();
        $request = array_merge($data, ['name' => 'siap']);
        $this->loginAs()
            ->patch(route('customer_type.update', $customer_type), $request)
            ->assertStatus(302);
        $customer_type_original = CustomerType::where('name', $data['name'])->first();
        $customer_type_updated = CustomerType::where('name', $request['name'])->first();
        $this->assertTrue(is_null($customer_type_original));
        $this->assertTrue(!is_null($customer_type_updated));
        $this->assertFlashLevel('success', __('app.global.message.success.update', [
            'item' => ucfirst('customer_type')
        ]));
    }

    /** @test */
    public function it_cant_see_delete_customer_type(): void
    {
        $customer_type = factory(CustomerType::class)->create();
        $this->loginAs()
            ->delete(route('customer_type.destroy', $customer_type))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_customer_type(): void
    {
        $this->assignPermission('delete-customer_type');
        $customer_type = factory(CustomerType::class)->create();
        $this->loginAs()
            ->delete(route('customer_type.destroy', $customer_type))
            ->assertStatus(302);
        $customer_type_deleted = CustomerType::find($customer_type->id);
        $this->assertTrue(is_null($customer_type_deleted));
        $this->assertFlashLevel('success', __('app.global.message.success.delete', [
            'item' => ucfirst('customer_type')
        ]));
    }

    /** @test */
    public function it_cant_see_bulk_delete_customer_type(): void
    {
        $ids = [
            'ids' => []
        ];
        $this->loginAs()
            ->delete(route('customer_type.bulkDestroy'), $ids)
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_cant_bulk_delete_customer_type_validation_error(): void
    {
        $this->assignPermission('bulk-delete-customer_type');
        $ids = [
            'ids' => null
        ];
        $this->loginAs()
            ->delete(route('customer_type.bulkDestroy'), $ids)
            ->assertSessionHasErrors('ids')
            ->assertStatus(302);
    }

    /** @test */
    public function it_can_bulk_delete_customer_type(): void
    {
        $this->assignPermission('bulk-delete-customer_type');
        $customer_types = factory(CustomerType::class, 2)->create();
        $id = $customer_types->pluck('id');
        $ids = [
            'ids' => $id->toArray()
        ];
        $this->loginAs()
            ->delete(route('customer_type.bulkDestroy'), $ids)
            ->assertStatus(302);
        $customer_type_deleted = CustomerType::find($id);
        $this->assertTrue($customer_type_deleted->isEmpty());
        $this->assertFlashLevel('success', __('app.global.message.success.bulk-delete', [
            'item' => ucfirst('customer_type')
        ]));
    }

    private function data(): array
    {
        return [
            'name' => $this->faker->name(),
            'default_point' => $this->faker->randomDigit
        ];
    }
}

