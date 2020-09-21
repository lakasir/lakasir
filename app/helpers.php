<?php

use App\Models\Media;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

if (! function_exists('action')) {
    function action(array $action)
    {
    }
}

if (! function_exists('dash_to_space')) {
    function dash_to_space(string $string, bool $capital = false)
    {
        $name = str_replace('-', ' ', $string);
        $name = str_replace('_', ' ', $name);

        return $capital ? Str::upper($name) : $name;
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
if (! function_exists('media')) {
    function media(Media $media = null)
    {
        return url($media->getFullName ?? '');
    }
}
if (! function_exists('medias')) {
    function medias(Collection $media)
    {
        dd($media);
    }
}
