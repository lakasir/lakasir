<?php

namespace App\Http\Requests\Tenants\Master;

use App\Services\Tenants\StockService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockRequest extends FormRequest
{
    private StockService $stockService;

    public function __construct()
    {
        $this->stockService = new StockService();
    }

    public function authorize()
    {
        return $this->user()->can('create product stock');
    }

    public function rules(): array
    {
        $product = $this->route('product');
        if ($product->is_non_stock) {
            throw ValidationException::withMessages([
                'product' => 'product is not non stock',
            ]);
        }

        if ($this->request->get('type') == null) {
            $this->request->set('type', 'in');
        }

        if ($this->request->get('date') == null) {
            $this->request->set('date', now()->format('Y-m-d'));
        }

        if (! $this->request->get('selling_price')) {
            $this->request->set('selling_price', $this->route('product')->selling_price);
        }

        if (! $this->request->get('initial_price')) {
            $this->request->set('initial_price', $this->route('product')->initial_price);
        }

        return [
            'type' => [Rule::in(['in', 'out'])],
            'stock' => ['required'],
            'initial_price' => ['numeric', 'nullable', 'lte:selling_price', function ($attribute, $value, $fail) {
                if ($this->request->get('type') == 'out') {
                    $fail("$attribute is not allowed for out type");

                    return false;
                }

                return true;
            }],
            'selling_price' => ['numeric', 'nullable', 'gte:initial_price', function ($attribute, $value, $fail) {
                if ($this->request->get('type') == 'out') {
                    $fail("$attribute is not allowed for out type");

                    return false;
                }

                return true;
            }],
        ];
    }

    public function store(): void
    {
        if ($this->request->get('type') == 'out') {
            $this->request->remove('initial_price');
            $this->request->remove('selling_price');
        }
        $this->request->add([
            'product_id' => $this->route('product')->id,
            'is_ready' => true,
        ]);
        $this->stockService->create($this->request->all());
    }
}
