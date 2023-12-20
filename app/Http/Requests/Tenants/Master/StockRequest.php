<?php

namespace App\Http\Requests\Tenants\Master;

use App\Models\Tenants\Stock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can("create product stock");
    }

    public function rules(): array
    {
        if ($this->request->get("type") == null) {
            $this->request->set("type", "in");
        }

        if ($this->request->get("date") == null) {
            $this->request->set("date", now()->format("Y-m-d"));
        }

        return [
            "type" => [Rule::in(["in", "out"])],
            "stock" => ["required"],
            "selling_price" => [
                Rule::requiredIf(function () {
                    if (!$this->request->get('initial_price') || $this->request->get('initial_price') == 0) {
                        return false;
                    }
                    return true;
                }),
                "gte:initial_price"
            ]
        ];
    }

    public function store(): void
    {
        $stock = new Stock();
        $stock->fill($this->request->all());
        $stock->product()->associate($this->route("product"));
        $stock->save();
    }
}
