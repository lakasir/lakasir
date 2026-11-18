<?php

namespace Modules\Warehouse\Support;

class Money
{
    /**
     * Turn a value that may still be money-masked into a float.
     *
     * Filament inputs with `->mask($money($input))` hand over strings such as
     * "37,000" or "37.000,50" while the user is typing. A plain `(float)`
     * cast of "37,000" yields 37, so parse the digits instead.
     */
    public static function toFloat(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);

        // Keep digits, one decimal separator and a leading minus.
        $value = preg_replace('/[^0-9,.\-]/', '', $value) ?? '';

        if ($value === '' || $value === '-') {
            return 0.0;
        }

        $lastComma = strrpos($value, ',');
        $lastDot = strrpos($value, '.');

        if ($lastComma !== false && $lastDot !== false) {
            // Whichever comes last is the decimal separator.
            if ($lastComma > $lastDot) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                $value = str_replace(',', '', $value);
            }
        } elseif ($lastComma !== false) {
            // A lone comma is a decimal separator unless it groups thousands.
            $value = preg_match('/,\d{3}\b/', $value)
                ? str_replace(',', '', $value)
                : str_replace(',', '.', $value);
        }

        return (float) $value;
    }
}
