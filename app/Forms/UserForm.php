<?php

namespace App\Forms;

use App\Models\Role;
use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

/**
 * Class UserForm
 * @author sheenazien8
 */
class UserForm extends ComponentAbstracts
{
    public function builder(): array
    {
        return [
            'username' => [
                'type' => 'text',
                'label' =>  __('app.user.column.username'),
                'placeholder' =>  __('app.user.placeholder.username'),
                'value' => optional($this->value->data)->username
            ],
            'email' => [
                'type' => 'text',
                'label' =>  __('app.user.column.email'),
                'placeholder' =>  __('app.user.placeholder.email'),
                'value' => optional($this->value->data)->email
            ],
            'password' => [
                'type' => 'password',
                'label' =>  __('app.user.column.password'),
                'placeholder' =>  __('app.user.placeholder.password'),
            ],
            'password_confirmation' => [
                'type' => 'password',
                'label' => trans('app.user.column.password_confirmation'),
                'placeholder' => trans('app.user.placeholder.password_confirmation'),
            ],
            'role' => view('app.user.components.role_select2', $this->data())
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

    public function validations(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['min:3']
        ];
    }

    private function data()
    {
        $data = $this->value->data;

        $roles = Role::toBase()->get()->map(function ($c) {
            return ['id' => $c->name, 'text' => $c->name];
        });

        return compact('data', 'roles');
    }

}
