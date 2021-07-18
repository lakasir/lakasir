<?php

namespace Tests\Feature\Master;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase as TestCase;

/** @package Tests\Feature\Master */
class SupplierTest extends TestCase
{
   use RefreshDatabase;

    /** @test */
    public function it_cant_browse_suppliers(): void
    {
        $this->loginAs()
            ->get(route('supplier.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_browse_suppliers(): void
    {
        $this->assignPermission('browse-supplier');
        $this->loginAs()
            ->get(route('supplier.index'))
            ->assertStatus(200)
            ->assertViewIs('app.master.suppliers.index')
            ->assertSeeText(__('app.suppliers.title'));
    }

    /** @test */
    public function it_can_browse_suppliers_via_ajax_datatbales(): void
    {
        $this->assignPermission('browse-supplier');
        $supplier = factory(Supplier::class)->create();
        $this->loginAs()
            ->getJson(route('supplier.index'), $this->ajaxHeader())
            ->assertJsonFragment([
                'name' => $supplier->name,
                'email' => $supplier->email,
                'code' => $supplier->code,
            ])
            ->assertSeeText($supplier->code)
            ->assertSeeText($supplier->email)
            ->assertSeeText($supplier->name);
    }

    /** @test */
    public function it_cant_see_create_form_supplier(): void
    {
        $this->loginAs()
            ->get(route('supplier.create'))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_create_form_supplier(): void
    {
        $this->assignPermission('create-supplier');
        $this->loginAs()
            ->get(route('supplier.create'))
            ->assertSeeText(__('app.suppliers.create.title'))
            ->assertSeeText(__('app.suppliers.column.name'))
            ->assertSeeText(__('app.suppliers.column.email'))
            ->assertSeeText(__('app.suppliers.column.code'))
            ->assertSeeText(__('app.suppliers.column.info.code'))
            ->assertSeeText(__('app.global.submit'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_store_supplier_validation_error_required(): void
    {
        $this->assignPermission('create-supplier');
        $request = array_merge($this->data(), ['name' => '', 'email' => '']);
        $this->loginAs()
            ->post(route('supplier.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name' => trans('validation.required', ['attribute' => 'name']),
                'email' => trans('validation.required', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function it_cant_store_supplier_validation_error_unique_email(): void
    {
        $this->assignPermission('create-supplier');
        $supplier = factory(Supplier::class)->create();
        $request = array_merge($this->data(), ['email' => $supplier->email]);
        $this->loginAs()
            ->post(route('supplier.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => trans('validation.unique', ['attribute' => 'email'])]);
    }

    /** @test */
    public function it_cant_store_supplier_validation_error_unique_code(): void
    {
        $this->assignPermission('create-supplier');
        $supplier = factory(Supplier::class)->create();
        $request = array_merge($this->data(), ['code' => $supplier->code]);
        $this->loginAs()
            ->post(route('supplier.store'), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors([ 'code' => trans('validation.unique', ['attribute' => 'code']) ]);
    }

    /** @test */
    public function it_can_store_supplier(): void
    {
        $this->assignPermission('create-supplier');
        $request = $this->data();
        $this->loginAs()
            ->post(route('supplier.store'), $request)
            ->assertStatus(302);
        $supplier_created = Supplier::where('email', $request['email'])->first();
        $this->assertTrue(!is_null($supplier_created));
        $this->assertTrue($request['name'] == $supplier_created->name);
        $this->assertTrue($request['email'] == $supplier_created->email);
        $this->assertTrue($request['supplier_type_id'] == $supplier_created->supplier_type_id);
        $this->assertFlashLevel('success', __('app.global.message.success.create', [
            'item' => ucfirst('supplier')
        ]));
    }

    /** @test */
    public function code_should_equals_with_format(): void
    {
        // expected_format = SUPYYYYMMDD001
        $this->assignPermission('create-supplier');
        $request = $this->data();
        $this->loginAs()
            ->post(route('supplier.store'), $request)
            ->assertStatus(302);
        $supplier_created = Supplier::where('email', $request['email'])->first();
        $this->assertTrue($supplier_created->code == 'SUPYYYYMMDD001');
        $this->assertFlashLevel('success', __('app.global.message.success.create', [
            'item' => ucfirst('supplier')
        ]));
    }

    /** @test */
    public function it_cant_see_edit_form_supplier(): void
    {
        $supplier = factory(Supplier::class)->create();
        $this->loginAs()
            ->get(route('supplier.edit', $supplier))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_see_edit_form_supplier(): void
    {
        $this->assignPermission('update-supplier');
        $supplier = factory(Supplier::class)->create();
        $this->loginAs()
            ->get(route('supplier.edit', $supplier))
            ->assertSeeText(__('app.suppliers.edit.title'))
            ->assertSeeText(__('app.suppliers.column.name'))
            ->assertSeeText(__('app.suppliers.column.email'))
            ->assertSeeText(__('app.suppliers.column.code'))
            ->assertSeeText(__('app.suppliers.column.info.code'))
            ->assertSeeText(__('app.global.submit'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_cant_update_supplier_validation_error(): void
    {
        $this->assignPermission('update-supplier');
        $supplier = factory(Supplier::class)->create();
        $request = array_merge($this->data(), ['name' => '']);
        $this->loginAs()
            ->patch(route('supplier.update', $supplier), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name' => trans('validation.required', ['attribute' => 'name'])]);
    }

    /** @test */
    public function it_cant_update_supplier_validation_error_uniqe_email(): void
    {
        $this->assignPermission('update-supplier');
        $supplier = factory(Supplier::class, 2)->create();
        $request = array_merge($this->data(), ['email' => $supplier->last()->email]);
        $this->loginAs()
            ->patch(route('supplier.update', $supplier->first()), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => trans('validation.unique', ['attribute' => 'email'])]);
    }

    public function it_cant_update_supplier_validation_error_uniqe_code(): void
    {
        $this->assignPermission('update-supplier');
        $supplier = factory(Supplier::class, 2)->create();
        $request = array_merge($this->data(), ['code' => $supplier->last()->code]);
        $this->loginAs()
            ->patch(route('supplier.update', $supplier->first()), $request)
            ->assertStatus(302)
            ->assertSessionHasErrors(['code' => trans('validation.unique', ['attribute' => 'code'])]);
    }

    /** @test */
    public function it_can_update_supplier(): void
    {
        $this->assignPermission('update-supplier');
        $origin_supplier = factory(Supplier::class)->create();
        $supplier_type_id = factory(SupplierType::class)->create();
        /* $data = $this->data(); */
        $data_update = [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'supplier_type_id' => $supplier_type_id->getKey()
        ];
        $id = $origin_supplier->getKey();
        $this->loginAs()
            ->patch(route('supplier.update', $origin_supplier), $data_update)
            ->assertStatus(302);
        $supplier_updated = Supplier::find($id);
        $this->assertTrue(!is_null($supplier_updated));
        $this->assertTrue($data_update['name'] == $supplier_updated->name);
        $this->assertTrue($data_update['email'] == $supplier_updated->email);
        $this->assertTrue($data_update['supplier_type_id'] == $supplier_updated->supplier_type_id);
        $this->assertFlashLevel('success', __('app.global.message.success.update', [
            'item' => ucfirst('supplier')
        ]));
    }

    /** @test */
    public function it_cant_see_delete_supplier(): void
    {
        $supplier = factory(Supplier::class)->create();
        $this->loginAs()
            ->delete(route('supplier.destroy', $supplier))
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_supplier(): void
    {
        $this->assignPermission('delete-supplier');
        $supplier = factory(Supplier::class)->create();
        $id = $supplier->id;
        $this->loginAs()
            ->delete(route('supplier.destroy', $supplier))
            ->assertStatus(302);
        $this->assertNull(Supplier::find($id));
        $this->assertFlashLevel('success', __('app.global.message.success.delete', [
            'item' => ucfirst('supplier')
        ]));
    }

    /** @test */
    public function it_cant_see_bulk_delete_supplier(): void
    {
        $ids = [
            'ids' => []
        ];
        $this->loginAs()
            ->delete(route('supplier.bulkDestroy'), $ids)
            ->assertSeeText(trans('app.auth.unauthorized'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_cant_bulk_delete_supplier_validation_error(): void
    {
        $this->assignPermission('bulk-delete-supplier');
        $ids = [
            'ids' => null
        ];
        $this->loginAs()
            ->delete(route('supplier.bulkDestroy'), $ids)
            ->assertSessionHasErrors('ids')
            ->assertStatus(302);
    }

    /** @test */
    public function it_can_bulk_delete_supplier(): void
    {
        $this->assignPermission('bulk-delete-supplier');
        $suppliers = factory(Supplier::class, 2)->create();
        $supplier_id = $suppliers->pluck('id');
        $ids = [
            'ids' => $supplier_id->toArray()
        ];
        $this->loginAs()
            ->delete(route('supplier.bulkDestroy'), $ids)
            ->assertStatus(302);
        $this->assertEmpty(Supplier::find($supplier_id));
        $this->assertFlashLevel('success', __('app.global.message.success.bulk-delete', [
            'item' => ucfirst('supplier')
        ]));
    }

    private function data(): array
    {
        return [ ];
    }
}
