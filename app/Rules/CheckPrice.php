<?php

namespace App\Rules;

use App\Repositories\Item;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class CheckPrice implements Rule
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var string
     */
    private $itemName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->items = new Item();
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
        for ($i = 0; $i < count($value); $i++) {
            $item = $this->items->find($value[$i]['item_id']);
            if (!$item->last_price) {
                $hasInitialPrice = Arr::exists($value[$i], 'initial_price');
                $hasSellingPrice = Arr::exists($value[$i], 'selling_price');
                if (!$hasInitialPrice && !$hasSellingPrice) {
                    $this->itemName = $item->name;

                    return false;
                } else{
                    /**
                     * TODO: logic if item has initial_price and selling_price <sheenazien8 2020-09-24>
                     *
                     */
                    return true;
                }
            } else {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('app.purchasings.validation.item_doesnot_have_price', ['item' => $this->itemName]);
    }
}
