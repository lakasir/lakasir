<?php

namespace App\Http\Requests\Install;

use Illuminate\Foundation\Http\FormRequest;

class Database extends FormRequest
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
            'host' => ['required', 'alpha_dash'],
            'name' => ['required', 'alpha_dash'],
            'username' => ['required', 'alpha_dash'],
            'password' => ['required', 'alpha_dash'],
        ];
    }
}
