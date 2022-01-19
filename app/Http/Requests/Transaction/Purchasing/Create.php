<?php

namespace App\Http\Requests\Transaction\Purchasing;

use App\Models\PaymentMethod;
use App\Traits\JsonValidateResponse;
use App\Traits\PurchasingTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Create extends FormRequest
{
    use PurchasingTrait, JsonValidateResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows("create-{$this->prefixPermission()}");
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
            // 'supplier' => ['required'],
            'payment_method' => [
                'required', function ($attribute, $value, $fail)
                {
                    /** @var \App\Models\PaymentMethod $paymentMethod */
                    $paymentMethod = PaymentMethod::find($value);
                    if (!$paymentMethod) {
                        return $fail("Payment method is not found");
                    }
                    if (!$paymentMethod->getArrayVisibleInAttribute()->purchasing) {
                        return $fail("this payment method is not allowed in this feature");
                    }
                }
            ],
            'items' => ['array', 'required']
        ];
    }
}
