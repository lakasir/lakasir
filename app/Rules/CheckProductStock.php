<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class CheckProductStock implements Rule, DataAwareRule
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

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($index)
    {
        $this->index = $index;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bool = Product::find($this->data['products'][$this->index]['product_id'])->stock <= $value;

        return !$bool;
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
