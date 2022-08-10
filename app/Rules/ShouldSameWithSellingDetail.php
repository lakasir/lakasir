<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ShouldSameWithSellingDetail implements Rule
{
    private $message;

    private $equals;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($equals)
    {
        $this->equals = $equals;
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
        $this->message = Str::of($attribute)->replace("_", " ")->ucfirst();
        $products = request()->products;
        $totals = 0;
        foreach ($products as $product) {
            $totals += $product[$this->equals];
        }
        if ($value != $totals) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message . ' is invalid';
    }
}
