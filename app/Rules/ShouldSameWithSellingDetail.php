<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ShouldSameWithSellingDetail implements ValidationRule
{
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private $equals, private array $products)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->message = Str::of($attribute)->replace('_', ' ')->ucfirst();
        $products = $this->products;
        $totals = 0;
        foreach ($products as $product) {
            $totals += $product[$this->equals];
        }
        if ($value != $totals) {
            $fail($this->message());

            return;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message.' is invalid';
    }
}
