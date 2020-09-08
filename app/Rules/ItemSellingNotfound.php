<?php

namespace App\Rules;

use App\Repositories\Item;
use Illuminate\Contracts\Validation\Rule;

class ItemSellingNotfound implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $itemRequest = count($value);
        $itemsDb = 0;
        array_map( function($el) use (&$itemsDb)
        {
            $item = new Item();
            if ($item->query()->find($el['id'])) {
                $itemsDb++;

                return true;
            }
        }, $value);

        return $itemRequest == $itemsDb;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'app.selling.validation.item_not_found';
    }
}
