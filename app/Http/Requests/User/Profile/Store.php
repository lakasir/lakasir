<?php

namespace App\Http\Requests\User\Profile;

use Illuminate\Foundation\Http\FormRequest;

/** @package App\Http\Requests\User\Profile */
class Store extends FormRequest
{
    /* use JsonValidateResponse; */
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
            'phone' => [ 'required' ],
            'address' => ['required'],
            'photo_profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
