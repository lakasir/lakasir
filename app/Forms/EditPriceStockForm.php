<?php

namespace App\Forms;

use App\Models\Category;
use Illuminate\Contracts\Container\BindingResolutionException;
use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

/**
 * Class EditPriceStockForm
 * @author sheenazien8
 */
class EditPriceStockForm extends ComponentAbstracts
{
    /**
     * @return array
     * @throws BindingResolutionException
     */
    public function builder(): array
    {
        $schema = [
            'stock' => [
                'type' => 'number',
                'label' =>  __('app.items.column.stock.stock'),
                'placeholder' =>  __('app.items.placeholder.stock.stock'),
                'value' => optional($this->value->data ?? '')->stock,
            ],
            'initial_price' => [
                'type' => 'number',
                'label' =>  __('app.items.column.price.initial_price'),
                'placeholder' =>  __('app.items.placeholder.price.initial_price'),
                'value' => optional(optional(optional($this->value->data ?? '')->prices)->last())->initial_price,
            ],
            'selling_price' => [
                'type' => 'number',
                'label' =>  __('app.items.column.price.selling_price'),
                'placeholder' =>  __('app.items.placeholder.price.selling_price'),
                'value' => optional(optional(optional($this->value->data ?? '')->prices)->last())->selling_price,
            ],
        ];
        return $schema;
    }

    /**
     * get buttons
     *
     * @return array
     * @throws BindingResolutionException
     */
    public function buttons(): array
    {
        return [
            [
                'label' => trans('app.global.submit'),
                'color' => 'primary'
            ],
        ];
    }

    /**
     * get option description
     *
     * @return array
     * @throws BindingResolutionException
     */
    private function categories(): array
    {
        $customer_type_map = Category::get()->map(function($customer_type) {
            return [
                'text' => $customer_type->name,
                'value' => $customer_type->getKey()
            ];
        })->toArray();

        return array_merge([['text' => __('app.items.placeholder.category.name'), 'value' => null]], $customer_type_map);
    }
}
