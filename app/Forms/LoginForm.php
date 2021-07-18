<?php

namespace App\Forms;

use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

class LoginForm extends ComponentAbstracts
{
    public function builder(): array
    {
        return [
            'identity' => [
                'type' => 'text',
                'label' =>  __('app.auth.label.identity'),
                'placeholder' =>  __('app.auth.placeholder.identity'),
            ],
            'password' => [
                'type' => 'password',
                'label' =>  __('app.auth.label.password'),
                'placeholder' =>  __('app.auth.placeholder.password'),
            ],
            'remember' => [
                'type' => 'checkbox',
                'label' => __('app.auth.label.remember')
            ]
        ];
    }

    public function buttons(): array
    {
        return [
            [
                'label' => trans('app.auth.login'),
                'color' => 'primary'
            ],
        ];
    }

    public function validations(): array
    {
        return [
            'identity' => ['required', 'min:3'],
            'password' => ['required', 'min:3'],
        ];
    }
}
