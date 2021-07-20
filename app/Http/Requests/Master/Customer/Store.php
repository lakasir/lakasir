<?php

namespace App\Http\Requests\Master\Customer;

use App\Traits\Customer\CustomerTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Store extends FormRequest
{
    use CustomerTrait;
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
            'name' => 'required',
            'email' => 'required',
        ];
    }
}
