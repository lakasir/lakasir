<?php

namespace App\Http\Requests\Master\Group;

use App\Traits\Group\GroupTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Create extends FormRequest
{
    use GroupTrait;
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
        return [
            'name' => 'required',
            'customers' => 'required'
        ];
    }
}
