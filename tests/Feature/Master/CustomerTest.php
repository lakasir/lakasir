<?php

namespace Tests\Feature\Master;

use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase as TestCase;

/** @package Tests\Feature\Master */
class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_browse_customers(): void
    {
        $this->loginAs()
            ->get(route('customer.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_browse_customers(): void
    {
        $this->assignPermission('browse-customer');
        $this->loginAs()
            ->get(route('customer.index'))
            ->assertStatus(200)
            ->assertViewIs('app.master.customers.index')
            ->assertSeeText(__('app.customers.title'));
    }

    /** @test */
    public function it_can_browse_customers_via_ajax_datatbales(): void
    {
        $this->assignPermission('browse-customer');
        $customer = factory(Customer::class)->create();
        $this->loginAs()
            ->getJson(route('customer.index'), $this->ajaxHeader())
            ->assertJsonFragment([
                'name' => $customer->name,
                'email' => $customer->email,
                'code' => $customer->code,
            ])
            ->assertSeeText($customer->code)
            ->assertSeeText($customer->email)
            ->assertSeeText($customer->name);
    }

    /** @test */
    public function it_cant_see_create_form_customer(): void
    {
        $this->loginAs()
            ->get(route('customer.create'))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_create_form_customer(): void
    {
        $this->assignPermission('create-customer');
        $this->loginAs()
            ->get(route('customer.create'))
            ->assertSeeText(__('app.customers.create.title'))
            ->assertSeeText(__('app.customers.column.name'))
            ->assertSeeText(__('app.customers.column.email'))
            ->assertSeeText(__('app.customers.column.code'))
            ->assertSeeText(__('app.customers.column.info.code'))
            ->assertSeeText(__('app.global.submit'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_store_customer_validation_error_required(): void
    {
        $this->assignPermission('create-customer');
        $request = array_merge($this->data(), ['name' => '', 'email' => '']);
        $this->loginAs()
            ->post(route('customer.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name' => trans('validation.required', ['attribute' => 'name']),
                'email' => trans('validation.required', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function it_cant_store_customer_validation_error_unique_email(): void
    {
        $this->assignPermission('create-customer');
        $customer = factory(Customer::class)->create();
        $request = array_merge($this->data(), ['email' => $customer->email]);
        $this->loginAs()
            ->post(route('customer.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => trans('validation.unique', ['attribute' => 'email'])]);
    }

    /** @test */
    public function it_cant_store_customer_validation_error_unique_code(): void
    {
        $this->assignPermission('create-customer');
        $customer = factory(Customer::class)->create();
        $request = array_merge($this->data(), ['code' => $customer->code]);
        $this->loginAs()
            ->post(route('customer.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors([ 'code' => trans('validation.unique', ['attribute' => 'code']) ]);
    }

    /** @test */
    public function it_can_store_customer(): void
    {
        $this->assignPermission('create-customer');
        $request = $this->data();
        $this->loginAs()
            ->post(route('customer.store'), $request)
            ->assertStatus(302);
        $customer_created = Customer::where('email', $request['email'])->first();
        $this->assertTrue(!is_null($customer_created));
        $this->assertTrue($request['name'] == $customer_created->name);
        $this->assertTrue($request['email'] == $customer_created->email);
        $this->assertTrue($request['customer_type_id'] == $customer_created->customer_type_id);
        $this->assertFlashLevel('success', __('app.global.message.success.create', [
            'item' => ucfirst('customer')
        ]));
    }

    /** @test */
    public function code_should_equals_with_format(): void
    {
        // expected_format = CUSYYYYMMDD001 -> increment
        $this->assignPermission('create-customer');
        $prefix_expected_number = 'CUS'.now()->format('Ymd');
        foreach (range(1, 50) as $key) {
            $request = $this->data();
            $this->loginAs()
                 ->post(route('customer.store'), $request)
                 ->assertStatus(302);
            /** @var Customer $customer_created */
            $customer_created = Customer::where('email', $request['email'])->first();
            $this->assertEquals($prefix_expected_number . str_pad($key, 3, 0, STR_PAD_LEFT), $customer_created->code);
            /* $this->assertTrue($customer_created->code == $prefix_expected_number . str_pad($key, 3, 0, STR_PAD_LEFT)); */
            $this->assertFlashLevel('success', __('app.global.message.success.create', [
                'item' => ucfirst('customer')
            ]));
        }
    }

    /** @test */
    public function it_cant_see_edit_form_customer(): void
    {
        $customer = factory(Customer::class)->create();
        $this->loginAs()
            ->get(route('customer.edit', $customer))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_edit_form_customer(): void
    {
        $this->assignPermission('update-customer');
        $customer = factory(Customer::class)->create();
        $this->loginAs()
            ->get(route('customer.edit', $customer))
            ->assertSeeText(__('app.customers.edit.title'))
            ->assertSeeText(__('app.customers.column.name'))
            ->assertSeeText(__('app.customers.column.email'))
            ->assertSeeText(__('app.customers.column.code'))
            ->assertSeeText(__('app.customers.column.info.code'))
            ->assertSeeText(__('app.global.submit'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_update_customer_validation_error(): void
    {
        $this->assignPermission('update-customer');
        $customer = factory(Customer::class)->create();
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->patch(route('customer.update', $customer), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);
    }

    /** @test */
    public function it_cant_update_customer_validation_error_uniqe_email(): void
    {
        $this->assignPermission('update-customer');
        $customer = factory(Customer::class, 2)->create();
        $request = array_merge($this->data(), ['email' => $customer->last()->email]);
        $this->loginAs()
            ->patch(route('customer.update', $customer->first()), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => trans('validation.unique', ['attribute' => 'email'])]);
    }

    /** @test */
    public function it_cant_update_customer_validation_error_uniqe_code(): void
    {
        $this->assignPermission('update-customer');
        $customer = factory(Customer::class, 2)->create();
        $request = array_merge($this->data(), ['code' => $customer->last()->code]);
        $this->loginAs()
            ->patch(route('customer.update', $customer->first()), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['code' => trans('validation.unique', ['attribute' => 'code'])]);
    }

    /** @test */
    public function it_can_update_customer(): void
    {
        $this->assignPermission('update-customer');
        $origin_customer = factory(Customer::class)->create();
        $customer_type_id = factory(CustomerType::class)->create();
        /* $data = $this->data(); */
        $data_update = [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'customer_type_id' => $customer_type_id->getKey()
        ];
        $id = $origin_customer->getKey();
        $this->loginAs()
            ->patch(route('customer.update', $origin_customer), $data_update)
            ->assertStatus(302);
        $customer_updated = Customer::find($id);
        $this->assertTrue(!is_null($customer_updated));
        $this->assertTrue($data_update['name'] == $customer_updated->name);
        $this->assertTrue($data_update['email'] == $customer_updated->email);
        $this->assertTrue($data_update['customer_type_id'] == $customer_updated->customer_type_id);
        $this->assertFlashLevel('success', __('app.global.message.success.update', [
            'item' => ucfirst('customer')
        ]));
    }

    /** @test */
    public function it_cant_see_delete_customer(): void
    {
        $customer = factory(Customer::class)->create();
        $this->loginAs()
            ->delete(route('customer.destroy', $customer))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_customer(): void
    {
        $this->assignPermission('delete-customer');
        $customer = factory(Customer::class)->create();
        $id = $customer->id;
        $this->loginAs()
            ->delete(route('customer.destroy', $customer))
            ->assertStatus(302);
        $this->assertNull(Customer::find($id));
        $this->assertFlashLevel('success', __('app.global.message.success.delete', [
            'item' => ucfirst('customer')
        ]));
    }

    /** @test */
    public function it_cant_see_bulk_delete_customer(): void
    {
        $ids = [
            'ids' => []
        ];
        $this->loginAs()
            ->delete(route('customer.bulkDestroy'), $ids)
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_cant_bulk_delete_customer_validation_error(): void
    {
        $this->assignPermission('bulk-delete-customer');
        $ids = [
            'ids' => null
        ];
        $this->loginAs()
            ->delete(route('customer.bulkDestroy'), $ids)
            ->assertSessionHasErrors('ids')
            ->assertStatus(302);
    }

    /** @test */
    public function it_can_bulk_delete_customer(): void
    {
        $this->assignPermission('bulk-delete-customer');
        $customers = factory(Customer::class, 2)->create();
        $customer_id = $customers->pluck('id');
        $ids = [
            'ids' => $customer_id->toArray()
        ];
        $this->loginAs()
            ->delete(route('customer.bulkDestroy'), $ids)
            ->assertStatus(302);
        $this->assertEmpty(Customer::find($customer_id));
        $this->assertFlashLevel('success', __('app.global.message.success.bulk-delete', [
            'item' => ucfirst('customer')
        ]));
    }

    /**
     * @return array
     * @throws BindingResolutionException
     */
    private function data(): array
    {
        $customer_type = factory(CustomerType::class)->create();

        return [
            'name' => $this->faker->randomLetter,
            'customer_type_id' => $customer_type->getKey(),
            'email' => $this->faker->email,
        ];
    }

}
