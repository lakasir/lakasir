<?php

namespace App\Forms;

use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

/**
 * Class UserForm
 * @author sheenazien8
 */
class CustomerForm extends ComponentAbstracts
{
    public function builder(): array
    {
        return [
            'code' => [
                'type' => 'text',
                'label' =>  __('app.customers.column.code'),
                'placeholder' =>  __('app.customers.placeholder.code'),
                'info' => __('app.customers.info.code'),
                'value' => optional($this->value->data ?? '')->code
            ],
            'name' => [
                'type' => 'text',
                'label' =>  __('app.customers.column.name'),
                'placeholder' =>  __('app.customers.placeholder.name'),
                'value' => optional($this->value->data ?? '')->name
            ],
            'email' => [
                'type' => 'text',
                'label' =>  __('app.customers.column.email'),
                'placeholder' =>  __('app.customers.placeholder.email'),
                'value' => optional($this->value->data ?? '')->email
            ],
            'customer_type_id' => view('app.master.customers.components.select-customer-type', [
                'data' => $this->value->data
            ])
        ];
    }

    public function buttons(): array
    {
        return [
            [
                'label' => trans('app.global.submit'),
                'color' => 'primary'
            ],
        ];
    }
}
