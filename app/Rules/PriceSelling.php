<?php

namespace App\Rules;

use App\Repositories\Item;
use Illuminate\Contracts\Validation\Rule;

class PriceSelling implements Rule
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var float
     */
    private $totalPrice;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $item = new Item();
        $items = $this->items;
        $totalPrice = $item->totalPriceByRequest($items);
        $this->totalPrice = $totalPrice;

        return $value >= $totalPrice;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('app.selling.validation.less_price', ['money' => $this->totalPrice]);
    }
}
