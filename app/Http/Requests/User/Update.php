<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use App\Traits\User\UserTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/** @package App\Http\Requests\User */
class Update extends FormRequest
{
    use UserTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("update-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        if (!in_array($this->method(), ['PUT', 'PATCH'])) {
            return [];
        }
        $user = $request->route()->parameters()["user"];
        $role_rule = Role::get()->pluck("name")->toArray();
        if (Hash::check($user->getKey(), $request->input("key-bypass-update"))) {
            return [
                "role" => Rule::in($role_rule)
            ];
        }
        return [
            "username" => "required|unique:users,username,{$user->getKey()}|min:5",
            "email" => "required|unique:users,email,{$user->getKey()}|min:5|email",
            "password" => "required|confirmed|min:5",
            "role" => Rule::in($role_rule)
        ];
    }
}
