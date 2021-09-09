<?php

namespace App\Forms;

use Sheenazien8\LivewireComponents\Abstracts\ComponentAbstracts;

/**
 * Class UserForm
 * @author sheenazien8
 */
class ProfileForm extends ComponentAbstracts
{
    public function builder(): array
    {
        /** @var \App\Models\User */
        $user = $this->value->auth;
        return [
            'phone' => [
                'type' => 'text',
                'label' =>  __('app.profiles.column.phone'),
                'placeholder' =>  __('app.profiles.placeholder.phone'),
                'value' => optional($user->profile ?? '')->phone
            ],
            'address' => [
                'type' => 'text',
                'label' =>  __('app.profiles.column.address'),
                'placeholder' =>  __('app.profiles.placeholder.address'),
                'value' => optional($user->profile ?? '')->address
            ],
            'bio' => [
                'type' => 'textarea',
                'label' =>  __('app.profiles.column.bio'),
                'placeholder' =>  __('app.profiles.placeholder.bio'),
                'value' => optional($user->profile ?? '')->bio
            ],
            'photo_profile' => [
                'type' => 'file',
                'label' =>  __('app.profiles.column.photo_profile'),
                'placeholder' =>  __('app.profiles.placeholder.photo_profile'),
                'value' => optional($user->profile ?? '')->photo_profile
            ],
            'lang' => [
                'type' => 'select',
                'label' =>  __('app.profiles.column.lang'),
                'placeholder' =>  __('app.profiles.placeholder.lang'),
                'value' => optional($user->profile ?? '')->lang,
                'option' => [
                    [
                        'value' => 'id',
                        'text' => 'Bahasa Indonesia'
                    ],
                    [
                        'value' => 'en',
                        'text' => 'English USA'
                    ],
                ]
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
}
