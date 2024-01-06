<?php

namespace App\Rules;

use App\Models\Tenants\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class CheckProductStock implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * key index from the data
     *
     * @var int
     */
    protected $index;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = Str::of($attribute)->explode('.')[1];
        $bool = Product::find($this->data['products'][$index]['product_id'])?->stock < $value;

        if ($bool) {
            $fail($this->message());
        }
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The stock of product less than from you request.';
    }
}
