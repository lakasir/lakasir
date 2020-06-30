<?php

namespace App\Http\Livewire;

use App\User;
use Kdion4891\LaravelLivewireForms\ArrayField;
use Kdion4891\LaravelLivewireForms\Field;
use Kdion4891\LaravelLivewireForms\FormComponent;

class InstallCreateForm extends FormComponent
{
    public function fields()
    {
        return [
            Field::make('Name')->input()->rules('required'),
        ];
    }

    public function success()
    {
        User::create($this->form_data);
    }

    public function saveAndStayResponse()
    {
        return redirect()->route('users.create');
    }

    public function saveAndGoBackResponse()
    {
        return redirect()->route('users.index');
    }
}
