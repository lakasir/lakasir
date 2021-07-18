<?php

namespace App\Http\Requests\Master\Item;

use App\Traits\Item\ItemTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Browse extends FormRequest
{
    use ItemTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->request->get('type') != 'select2') {
            return Gate::authorize("browse-{$this->prefixPermission()}");
        }
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
            'orderBy' => 'in:id,name,url_customize,website,status,sort|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            's' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'limit' => 'integer|nullable',
        ];
    }
}
