<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use App\Traits\User\UserTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{
    use UserTrait;
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
        if ($this->method() != 'POST') {
            return [];
        }
        $role_rule = Role::get()->pluck("name")->toArray();
        return [
            "username" => "required|unique:users,username|min:5",
            "email" => "required|unique:users,email|min:5|email",
            "password" => "required|confirmed|min:5",
            "role" => Rule::in($role_rule)
        ];
    }
}
