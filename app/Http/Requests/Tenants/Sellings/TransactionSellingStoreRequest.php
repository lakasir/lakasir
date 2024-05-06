<?php

namespace App\Http\Requests\Tenants\Sellings;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Rules\CheckProductStock;
use App\Rules\ShouldSameWithSellingDetail;
use App\Services\Tenants\SellingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TransactionSellingStoreRequest extends FormRequest
{
    public function __construct(public SellingService $sellingService)
    {

    }

    public function authorize(): bool
    {
        if (Setting::get('cash_drawer_enabled', false)) {
            $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
            if (! $lastOpenedCashDrawer) {
                throw ValidationException::withMessages([
                    'cash_drawer' => 'Cash drawer is not opened',
                ]);
            }
        }

        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge($this->sellingService->mapProductRequest($this->all()));
    }

    public function rules(): array
    {
        return [
            'fee' => ['numeric'],
            'payed_money' => ['required', 'gte:total_price'],
            'total_price' => ['required_if:friend_price,true', 'numeric'],
            'total_qty' => ['required_if:friend_price,true', 'numeric', new ShouldSameWithSellingDetail('qty', $this->products)],
            'friend_price' => ['required', 'boolean'],
            'products' => ['array'],
            'products.product_id' => ['required', 'exists:products,id'],
            'products.price' => ['required_if:friend_price,true', 'numeric'],
            'products.qty' => ['required', 'numeric', 'min:1', new CheckProductStock],
        ];
    }

    public function store(): Selling
    {
        return $this->sellingService->create($this->all());
    }
}
