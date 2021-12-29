<?php

namespace App\Forms;

use App\Models\Category;
use Illuminate\Contracts\Container\BindingResolutionException;
use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

/**
 * Class UserForm
 * @author sheenazien8
 */
class ItemForm extends ComponentAbstracts
{
    /**
     * @return array
     * @throws BindingResolutionException
     */
    public function builder(): array
    {
        $schema = [
            /* 'image' => [ */
            /*     'type' => 'file', */
            /*     'label' =>  __('app.items.column.images'), */
            /*     'placeholder' =>  __('app.items.placeholder.images'), */
            /*     'value' => optional($this->value->data ?? '')->image */
            /* ], */
            'category' => [
                'type' => 'options',
                'label' =>  __('app.items.column.category.name'),
                'placeholder' =>  __('app.customers.placeholder.category.name'),
                'value' => optional($this->value->data ?? '')->category_id ?? null,
                'option' => $this->categories()
            ],
            'inline' => [
                [
                    'name' => [
                        'type' => 'text',
                        'label' =>  __('app.items.column.name'),
                        'placeholder' =>  __('app.items.placeholder.name'),
                        'value' => optional($this->value->data ?? '')->name,
                        'col' => 'col-md-4'
                    ],
                    'sku' => [
                        'type' => 'text',
                        'label' =>  __('app.items.column.sku'),
                        'placeholder' =>  __('app.items.placeholder.sku'),
                        'value' => optional($this->value->data ?? '')->sku,
                        'col' => 'col-md-4'
                    ],
                    'unit' => [
                        'type' => 'text',
                        'label' =>  __('app.items.column.unit.name'),
                        'placeholder' =>  __('app.items.placeholder.unit.name'),
                        'value' => optional($this->value->data ?? '')->unit,
                        'col' => 'col-md-4'
                    ],
                ],
                [
                    'stock' => [
                        'type' => 'number',
                        'label' =>  __('app.items.column.stock.stock'),
                        'placeholder' =>  __('app.items.placeholder.stock.stock'),
                        'value' => optional($this->value->data ?? '')->stock,
                        'col' => 'col-md-4'
                    ],
                    'initial_price' => [
                        'type' => 'number',
                        'label' =>  __('app.items.column.price.initial_price'),
                        'placeholder' =>  __('app.items.placeholder.price.initial_price'),
                        'value' => optional(optional(optional($this->value->data ?? '')->prices)->last())->initial_price,
                        'col' => 'col-md-4'
                    ],
                    'selling_price' => [
                        'type' => 'number',
                        'label' =>  __('app.items.column.price.selling_price'),
                        'placeholder' =>  __('app.items.placeholder.price.selling_price'),
                        'value' => optional(optional(optional($this->value->data ?? '')->prices)->last())->selling_price,
                        'col' => 'col-md-4'
                    ],
                ],
            ],
            'internal_production' => [
                'type' => 'checkbox',
                'label' =>  __('app.items.column.internal_production'),
                'placeholder' =>  __('app.customers.placeholder.internal_production'),
                'value' => optional($this->value->data ?? '')->internal_production ?? null,
            ],
            'item_type' => [
                'type' => 'radio',
                'label' =>  __('app.items.column.item_type.name'),
                'placeholder' =>  __('app.customers.placeholder.item_type.name'),
                'value' => optional($this->value->data ?? '')->item_type ?? 0,
                'radios' => [
                    ['label' => __('app.items.column.item_type.label.default'), 'value' => __('app.items.column.item_type.value.default'), 'key' => 'default', 'col' => 'col-md col-sm-12'],
                    ['label' => __('app.items.column.item_type.label.imei'), 'value' => __('app.items.column.item_type.value.imei'), 'key' => 'imei', 'col' => 'col-md col-sm-12'],
                    ['label' => __('app.items.column.item_type.label.variant'), 'value' => __('app.items.column.item_type.value.variant'), 'key' => 'variant', 'col' => 'col-md col-sm-12'],
                    ['label' => __('app.items.column.item_type.label.multi_unit'), 'value' => __('app.items.column.item_type.value.multi_unit'), 'key' => 'multi_unit', 'col' => 'col-md col-sm-12'],
                    ['label' => __('app.items.column.item_type.label.package'), 'value' => __('app.items.column.item_type.value.package'), 'key' => 'package', 'col' => 'col-md col-sm-12'],
                    ['label' => __('app.items.column.item_type.label.raw_material'), 'value' => __('app.items.column.item_type.value.raw_material'), 'key' => 'raw_material', 'col' => 'col-md col-sm-12'],
                ]
            ],
        ];
        if (in_array($this->getMethod(), ['PUT', 'PATCH'])) {
            unset($schema['inline'][1]);
        }
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

        return array_merge([
            ['text' => __('app.items.create.category'), 'value' => route('category.create'), 'type' => 'link'],
            ['text' => __('app.items.placeholder.category.name'), 'value' => null],
        ], $customer_type_map);
    }
}
