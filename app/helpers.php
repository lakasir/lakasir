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
        $name = str_replace('-', ' ', Str::title($string));
        $name = str_replace('_', ' ', Str::title($name));
        return $name;
    }
}
