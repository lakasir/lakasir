<?php

namespace App\Http\Requests\Transaction\Purchasing;

use App\Traits\PurchasingTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Create extends FormRequest
{
    use PurchasingTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("create-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() == "GET") {
            return [];
        }
        return [
            'supplier_id' => ['required'],
            'payment_method' => [
                'required',
            ],
            'items' => ['array', 'required']
        ];
    }
}
