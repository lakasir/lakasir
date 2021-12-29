<?php

namespace App\Forms;

use App\Models\CustomerType;
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
            'customer_type' => [
                'type' => 'select2',
                'label' =>  __('app.customers.column.customer_type'),
                'placeholder' =>  __('app.customers.placeholder.customer_type'),
                'value' => optional($this->value->data ?? '')->customer_type ?? 0,
                'add-url' => route('customer_type.create', ['from' => request()->getPathInfo()]),
                'option' => $this->customerType()
            ]
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

    private function customerType(): array
    {
        $customer_type_map = CustomerType::get()->map(function($customer_type) {
            return [
                'text' => $customer_type->name,
                'value' => $customer_type->getKey()
            ];
        })->toArray();

        return array_merge([
            ['text' => __('app.customer.placeholder.customer_type.none'), 'value' => 0]
        ], $customer_type_map);
    }
}
