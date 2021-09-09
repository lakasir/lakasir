<?php

namespace App\Forms;

use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

/**
 * Class ChangePasswordForm
 * @author sheenazien8
 */
class ChangePasswordForm extends ComponentAbstracts
{
    public function builder(): array
    {
        return [
            'old_password' => [
                'type' => 'password',
                'label' =>  __('app.user.change_password.column.old_password'),
                'placeholder' =>  __('app.user.change_password.placeholder.old_password'),
            ],
            'new_password' => [
                'type' => 'password',
                'label' =>  __('app.user.change_password.column.new_password'),
                'placeholder' =>  __('app.user.change_password.placeholder.new_password'),
            ],
            'new_password_confirmation' => [
                'type' => 'password',
                'label' =>  __('app.user.change_password.column.new_password_confirmation'),
                'placeholder' =>  __('app.user.change_password.placeholder.new_password_confirmation'),
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
        ];
    }
}
