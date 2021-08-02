<?php

namespace App\Forms;

use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

class SupplierForm extends ComponentAbstracts
{
    public function builder(): array
    {
        return [
            'code' => [
                'type' => 'text',
                'label' =>  __('app.suppliers.column.code'),
                'placeholder' =>  __('app.suppliers.placeholder.code'),
                'value' => optional($this->value->data)->code,
                'info' => __('app.suppliers.info.code')
            ],
            'name' => [
                'type' => 'text',
                'label' =>  __('app.suppliers.column.name'),
                'placeholder' =>  __('app.suppliers.placeholder.name'),
                'value' => optional($this->value->data)->name,
            ],
            'shop_name' => [
                'type' => 'text',
                'label' =>  __('app.suppliers.column.shop_name'),
                'placeholder' =>  __('app.suppliers.placeholder.shop_name'),
                'value' => optional($this->value->data)->shop_name,
            ],
            'email' => [
                'type' => 'text',
                'label' =>  __('app.suppliers.column.email'),
                'placeholder' =>  __('app.suppliers.placeholder.email'),
                'value' => optional($this->value->data ?? '')->email
            ],
            'phone' => [
                'type' => 'text',
                'label' =>  __('app.suppliers.column.phone'),
                'placeholder' =>  __('app.suppliers.placeholder.phone'),
                'value' => optional($this->value->data)->phone,
            ],
            'address' => [
                'type' => 'text',
                'label' =>  __('app.suppliers.column.address'),
                'placeholder' =>  __('app.suppliers.placeholder.address'),
                'value' => optional($this->value->data)->address,
            ],
        ];
    }

    public function buttons(): array
    {
        return [
            [
                'label' => trans('app.global.submit'),
                'color' => 'primary'
            ],
            [
                'label' => trans('app.global.cancel'),
                'color' => 'default',
                'link' => route('supplier.index')
            ]
        ];
    }

    // public function validations(): array
    // {
    //     return [
    //         'name' => ['required', 'min:3'],
    //         'shop_name' => ['required'],
    //         'phone' => ['required'],
    //         'address' => ['required'],
    //     ];
    // }
}
