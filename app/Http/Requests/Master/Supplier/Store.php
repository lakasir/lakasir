<?php

namespace App\Http\Requests\Master\Supplier;

use App\Traits\Supplier\SupplierTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Store extends FormRequest
{
    use SupplierTrait;
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
        if ($this->method() != 'POST') {
            return [];
        }

        return [
            'code' => 'nullable|unique:suppliers,code',
            'email' => 'required|email|unique:suppliers,email',
            'name' => 'required|string',
            'shop_name' => 'required|string',
            'phone' => 'required|unique:suppliers,phone',
            'address' => 'required',
        ];
    }
}
