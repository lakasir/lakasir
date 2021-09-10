<?php

namespace App\Http\Requests\User\Role;

use App\Traits\RoleTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/** @package App\Http\Requests\User\Role */
class Create extends FormRequest
{
    use RoleTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("create-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() != "POST") {
            return [];
        }
        return [
            'name' => 'required'
        ];
    }
}
