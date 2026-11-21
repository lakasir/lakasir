<?php

namespace App\Http\Controllers\Api\Tenants\Master;

use App\Http\Controllers\Controller;
use App\Models\Tenants\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * The supplier CRUD. Written out here rather than through the has-crud-action
 * package: its index resolver hands the query builder a model instance, so
 * `GET /api/master/supplier` was a 500. The shape of the responses is the same.
 */
class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = QueryBuilder::for(Supplier::class)
            ->allowedFilters(['email', 'name', 'phone_number', 'address', 'city', 'country'])
            ->orderByDesc('created_at')
            ->simplePaginate();

        return $this->success($suppliers->items());
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $supplier = Supplier::create($request->all());

        return $this->success($supplier, 'success creating items');
    }

    public function show(Supplier $supplier)
    {
        return $this->success($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request, $this->rules($supplier));

        $supplier->update($request->all());

        return $this->success($supplier, 'success update items');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return $this->success([], 'success delete items');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?Supplier $supplier = null): array
    {
        return [
            'name' => ['required'],
            'phone_number' => [Rule::unique('suppliers', 'phone_number')->ignore($supplier?->id)],
            'email' => [Rule::unique('suppliers', 'email')->ignore($supplier?->id)],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
        ];
    }
}
