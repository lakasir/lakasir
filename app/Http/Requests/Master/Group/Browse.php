<?php

namespace App\Http\Requests\Master\Group;

use App\Traits\Group\GroupTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Browse extends FormRequest
{
    use GroupTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("browse-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,name,url_customize,website,status,sort|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            's' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'limit' => 'integer|nullable',
        ];
    }
}
