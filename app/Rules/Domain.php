<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Domain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string = null): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! str($value)->contains(config('tenancy.central_domains')[0])) {
            $value = $value.'.'.config('tenancy.central_domains')[0];
        }

        $domainPattern = '/^[a-z](?:[a-z0-9]+(?:-[a-z0-9]+)*)?\.'.config('tenancy.central_domains')[0].'$/u';

        if (preg_match('/\s/', $value)) {
            $fail("The $attribute cannot contain spaces.");
        } elseif (! preg_match($domainPattern, $value)) {
            $fail("The $attribute is not a valid domain.");
        }
    }
}
