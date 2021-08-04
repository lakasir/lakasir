<?php

namespace Tests\Feature\Master;

use App\Models\Customer;
use App\Models\CustomerPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase as TestCase;

class CustomerPointTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_browse_customer_points_via_permissions(): void
    {
        $customer = factory(Customer::class)->create();
        $this->loginAs()
            ->get(route('customer_point.index', ['customer' => $customer]))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_browse_customer_points(): void
    {
        $this->assignPermission('browse-customer_point');
        $customer = factory(Customer::class)->create();
        $this->loginAs()
            ->get(route('customer_point.index', ['customer' => $customer]))
            ->assertStatus(200)
            ->assertViewIs('app.master.customer_points.index')
            ->assertSeeText(trans('app.customer_points.title'));
    }

    public function it_can_browse_customer_points_via_ajax_datatbales(): void
    {
        $this->assignPermission('browse-customer_point');
        /** @var Customer $customer */
        $customer = factory(Customer::class)->create();
        $customer_points = $customer->customerPoints;
        $this->loginAs()
            ->getJson(route('customer_point.index', ['customer' => $customer]), $this->ajaxHeader())
            ->assertJsonFragment([
                'name' => $customer->name,
            ])
            ->assertSeeText($customer->name)
            ->assertSeeText($customer_points->first()->point)
            ->assertSeeText($customer_points->first()->date);
    }

    /* TODO: bingung testingnya karena menggunakan modal <31-07-21, @sheenazien8> */
    public function it_cant_see_create_form_customer_point(): void
    {
        /** pake modal di action customer */
        $this->loginAs()
            ->get(route('customer_point.create', ['customer' => factory(Customer::class)->create() ]))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /* TODO: bingung testingnya karena menggunakan modal <31-07-21, @sheenazien8> */
    public function it_can_see_create_form_customer_point(): void
    {
        $customer = factory(Customer::class)->create();
        $this->assignPermission('create-customer_point');
        $this->loginAs()
            ->get(route('customer_point.create', ['customer' => $customer]))
            ->assertSeeText(trans('app.customer_points.create.title'))
            ->assertSeeText(trans('app.customer_points.column.name'))
            ->assertSeeText(trans('app.customer_points.column.email'))
            ->assertSeeText(trans('app.customer_points.column.code'))
            ->assertSeeText(trans('app.customer_points.info.code'))
            ->assertSeeText(trans('app.global.submit'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_store_customer_point_validation_error_required(): void
    {
        $this->assignPermission('create-customer_point');
        $customer = factory(Customer::class)->create();
        $request = array_merge($this->data(), ['point' => '' ]);
        $this->loginAs()
            ->post(route('customer_point.store', ['customer' => $customer]), $request)
            ->assertStatus(302)
            ->assertSeeText(trans('validation.required', ['attribute' => 'point']))
            ->assertSessionHasErrors([
                'point' => trans('validation.required', ['attribute' => 'point']),
            ]);
    }

    /** @test */
    public function it_can_store_customer_point(): void
    {
        $this->assignPermission('create-customer_point');
        $customer = factory(Customer::class)->create();
        for ($i = 0; $i < 5; $i++) {
            $recent_points = 0;
            if ($customer->customerPoints->count() > 0) {
                $recent_points = $customer->customerPoints()->sum('point');
            }
            $request = $this->data();
            $this->loginAs()
                 ->post(route('customer_point.store', ['customer' => $customer]), $request)
                 ->assertStatus(302);
            $customer_point_newest_created = $customer->customerPoints->last();
            $exptected_point_created = $recent_points + $request['point'];
            $this->assertEquals($exptected_point_created, $recent_points + $customer_point_newest_created->point);
            $this->assertFlashLevel('success', trans('app.global.message.success.create', [
                'item' => ucfirst('customer_point')
            ]));
            sleep(1);
        }
    }

    /** @test */
    public function it_cant_see_edit_form_customer_point(): void
    {
        $customer_point = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->get(route('customer_point.edit', $customer_point))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_edit_form_customer_point(): void
    {
        $this->assignPermission('update-customer_point');
        $customer_point = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->get(route('customer_point.edit', $customer_point))
            ->assertSeeText(trans('app.customer_points.edit.title'))
            ->assertSeeText(trans('app.customer_points.column.name'))
            ->assertSeeText(trans('app.customer_points.column.email'))
            ->assertSeeText(trans('app.customer_points.column.code'))
            ->assertSeeText(trans('app.customer_points.info.code'))
            ->assertSeeText(trans('app.global.submit'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_update_customer_point_validation_error(): void
    {
        $this->assignPermission('update-customer_point');
        $customer_point = factory(CustomerPoint::class)->create();
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->patch(route('customer_point.update', $customer_point), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);
    }

    /** @test */
    public function it_cant_update_customer_point_validation_error_uniqe_email(): void
    {
        $this->assignPermission('update-customer_point');
        $customer_point = factory(CustomerPoint::class, 2)->create();
        $request = array_merge($this->data(), ['email' => $customer_point->last()->email]);
        $this->loginAs()
            ->patch(route('customer_point.update', $customer_point->first()), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => trans('validation.unique', ['attribute' => 'email'])]);
    }

    /** @test */
    public function it_cant_update_customer_point_validation_error_uniqe_code(): void
    {
        $this->assignPermission('update-customer_point');
        $customer_point = factory(CustomerPoint::class, 2)->create();
        $request = array_merge($this->data(), ['code' => $customer_point->last()->code]);
        $this->loginAs()
            ->patch(route('customer_point.update', $customer_point->first()), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['code' => trans('validation.unique', ['attribute' => 'code'])]);
    }

    /** @test */
    public function it_can_update_customer_point(): void
    {
        $this->assignPermission('update-customer_point');
        $origin_customer_point = factory(CustomerPoint::class)->create();
        $customer_point_type_id = factory(CustomerPointType::class)->create();
        /* $data = $this->data(); */
        $data_update = [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'customer_point_type_id' => $customer_point_type_id->getKey()
        ];
        $id = $origin_customer_point->getKey();
        $this->loginAs()
            ->patch(route('customer_point.update', $origin_customer_point), $data_update)
            ->assertStatus(302);
        $customer_point_updated = CustomerPoint::find($id);
        $this->assertTrue(!is_null($customer_point_updated));
        $this->assertTrue($data_update['name'] == $customer_point_updated->name);
        $this->assertTrue($data_update['email'] == $customer_point_updated->email);
        $this->assertTrue($data_update['customer_point_type_id'] == $customer_point_updated->customer_point_type_id);
        $this->assertFlashLevel('success', trans('app.global.message.success.update', [
            'item' => ucfirst('customer_point')
        ]));
    }

    /** @test */
    public function it_cant_see_delete_customer_point(): void
    {
        $customer_point = factory(CustomerPoint::class)->create();
        $this->loginAs()
            ->delete(route('customer_point.destroy', $customer_point))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_customer_point(): void
    {
        $this->assignPermission('delete-customer_point');
        $customer_point = factory(CustomerPoint::class)->create();
        $id = $customer_point->id;
        $this->loginAs()
            ->delete(route('customer_point.destroy', $customer_point))
            ->assertStatus(302);
        $this->assertNull(CustomerPoint::find($id));
        $this->assertFlashLevel('success', trans('app.global.message.success.delete', [
            'item' => ucfirst('customer_point')
        ]));
    }

    /** @test */
    public function it_cant_see_bulk_delete_customer_point(): void
    {
        $ids = [
            'ids' => []
        ];
        $this->loginAs()
            ->delete(route('customer_point.bulkDestroy'), $ids)
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_cant_bulk_delete_customer_point_validation_error(): void
    {
        $this->assignPermission('bulk-delete-customer_point');
        $ids = [
            'ids' => null
        ];
        $this->loginAs()
            ->delete(route('customer_point.bulkDestroy'), $ids)
            ->assertSessionHasErrors('ids')
            ->assertStatus(302);
    }

    /** @test */
    public function it_can_bulk_delete_customer_point(): void
    {
        $this->assignPermission('bulk-delete-customer_point');
        $customer_points = factory(CustomerPoint::class, 2)->create();
        $customer_point_id = $customer_points->pluck('id');
        $ids = [
            'ids' => $customer_point_id->toArray()
        ];
        $this->loginAs()
            ->delete(route('customer_point.bulkDestroy'), $ids)
            ->assertStatus(302);
        $this->assertEmpty(CustomerPoint::find($customer_point_id));
        $this->assertFlashLevel('success', trans('app.global.message.success.bulk-delete', [
            'item' => ucfirst('customer_point')
        ]));
    }

    /**
     * @return array
     * @throws BindingResolutionException
     */
    private function data(): array
    {
        return [];
    }
}
