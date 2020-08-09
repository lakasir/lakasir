<?php

namespace App\Http\Requests\Master\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

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
            'name' => 'required',
            'stock' => ['required', 'integer'],
            'unit_id' => 'required',
            'category_id' => 'required',
            'initial_price' => ['required', 'min:1'],
            'selling_price' => ['required', 'min:1'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
