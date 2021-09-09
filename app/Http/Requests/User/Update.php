<?php

namespace App\Http\Requests\User;

use App\Traits\User\UserTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

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

        $routeParameters = $request->route()->parameters();
        return [ ];
    }
}
