<?php

namespace App\Http\Requests\Tenants\Vouchers;

use App\Services\VoucherService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class VoucherCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'total_price' => ['required', 'numeric', 'min:0'],
            'code' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! is_numeric($this->input('total_price'))) {
                        return;
                    }

                    $reason = app(VoucherService::class)->rejectionReason($value, (float) $this->input('total_price'));

                    if ($reason) {
                        $fail($reason);
                    }
                },
            ],
        ];
    }
}
