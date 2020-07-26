<?php

namespace App\Http\Requests\Transaction\Purchasing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Update extends FormRequest
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
            'supplier_id' => ['required'],
            'payment_method' => [
                'required',
                Rule::in(config('array_options.payment_method'))
            ],
            'items' => ['array', 'required']
        ];
    }
}
