<?php

namespace App\Http\Requests\Transaction\Selling;

use App\Rules\ItemSellingNotfound;
use App\Rules\PriceSelling;
use App\Traits\JsonValidateResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{

    use JsonValidateResponse;
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
            'items' => ['required', 'array', new ItemSellingNotfound()],
            'money' => ['required', new PriceSelling($this->items)],
            'payment_method_id' => ['required']
        ];
    }
}
