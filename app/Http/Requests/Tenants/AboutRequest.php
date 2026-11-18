<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_name' => ['nullable', 'string'],
            'shop_location' => ['nullable', 'string'],
            'business_type' => ['required', 'in:retail,wholesale,fnb,fashion,pharmacy,other'],
            'other_business_type' => ['required_if:business_type,other'],
            'owner_name' => ['nullable', 'string'],
            'uploaded_file_id' => ['nullable', 'integer', 'exists:uploaded_files,id'],
            'currency' => ['nullable', 'string'],
        ];
    }
}
