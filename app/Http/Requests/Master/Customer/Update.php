<?php

namespace App\Http\Requests\Master\Customer;

use App\Traits\Customer\CustomerTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    use CustomerTrait;
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

        $routeParameters = $request->route()->parameters();
        return [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('customers')->ignore($routeParameters['customer'])
            ],
            'code' => [
                'nullable',
                Rule::unique('customers')->ignore($routeParameters['customer'])
            ],
        ];
    }
}
