<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\JsonValidateResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Login extends FormRequest
{
    use JsonValidateResponse;
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
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
    }
}
