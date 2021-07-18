<?php

namespace App\Http\Requests\Master\Item;

use App\Traits\Item\ItemTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BulkDelete extends FormRequest
{
    use ItemTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("delete-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => 'required'
        ];
    }

    /** @return array  */
    public function messages()
    {
        $message = __('hascrudactions::app.global.message.fail.delete', [
            'item' => ucfirst($this->resources())
        ]);

        /* TODO: nambah pesan custom <18-05-21, sheenazien8> */
        return [
            ''
        ];
    }
}
