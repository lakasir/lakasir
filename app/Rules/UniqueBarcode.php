<?php

namespace App\Rules;

use App\Models\Tenants\Barcode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueBarcode implements ValidationRule
{
    protected ?int $excludedId;

    public function __construct(?int $excludedId = null)
    {
        $this->excludedId = $excludedId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $query = Barcode::where('code', $value);

        if ($this->excludedId !== null) {
            $query->where('id', '!=', $this->excludedId);
        }

        if ($query->exists()) {
            $fail(__('Barcode already exists.'));
        }
    }
}
