<?php

namespace App\Http\Requests\Master\Item;

use App\Traits\Item\ItemTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * Class UpdateStockRate
 * @author sheenazien8
 */
class UpdateStockRate extends FormRequest
{
    use ItemTrait;
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
        return [];
    }

}
