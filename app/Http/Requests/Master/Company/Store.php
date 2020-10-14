<?php

namespace App\Http\Requests\Master\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

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
            'address' => ['required'],
            'business_description' => ['required'],
            'business_type' => [Rule::in(config('array_options.business_type'))],
            'default_currency' => [Rule::in(Arr::pluck(config('array_options.default_currency'), 'id'))],
            'expected_max_employee' => ['min:1', 'numeric']
        ];
    }
}

