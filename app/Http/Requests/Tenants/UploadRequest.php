<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Only what the app actually shows: product/profile/about images, and the
     * product-import spreadsheet. SVG is left out on purpose — it can carry
     * script and the tmp disk is served from the app's own host.
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,bmp,csv,xls,xlsx', 'max:5120'],
        ];
    }
}
