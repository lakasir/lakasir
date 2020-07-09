<?php

namespace App\Http\Requests\Install;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'alpha_dash', 'min:3', 'unique:users'],
            'email' => ['required', 'email'],
            'password' => ['required', 'alpha_dash', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }
}
