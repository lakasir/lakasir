<?php

namespace App\Http\Requests\Master\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'code' => ['required', 'unique:payment_methods'],
            'visible_in' => ['array'],
        ];
    }
}
