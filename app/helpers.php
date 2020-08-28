<?php

use Illuminate\Support\Str;

if (! function_exists('action')) {
    function action(array $action)
    {
    }
}

if (! function_exists('dash_to_space')) {
    function dash_to_space(string $string)
    {
        $name = str_replace('-', ' ', Str::upper($string));
        $name = str_replace('_', ' ', Str::upper($name));
        return $name;
    }
}
if (! function_exists('price_format')) {
    function price_format($price)
    {
        return 'Rp. '.number_format($price, 0, ',', '.');
    }
}
if (! function_exists('get_lang')) {
    function get_lang()
    {
        app()->setLocale(optional(auth()->user() ?? 'en')->localization);
    }
}
