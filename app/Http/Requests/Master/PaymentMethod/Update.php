<?php

namespace App\Http\Requests\Master\PaymentMethod;

use App\Traits\PaymentMethod\PaymentMethodTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Update extends FormRequest
{
    use PaymentMethodTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("update-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!in_array($this->method(), ['PUT', 'PATCH'])) {
            return [];
        }
        return [
            'name' => 'required'
        ];
    }
}
