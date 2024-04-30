<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lakasir merupakan aplikasi point of sale (POS) yang memudahkan pengelolaan bisnis Anda. unduh secara gratis">
    <meta name="keywords" content="POS, open-source, gratis, free, murah">
    <meta name="author" content="Lakasir">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/logo/image.png">

    <!-- Open Graph tags for social media -->
    <meta property="og:title" content="Lakasir - Aplikasi Point of Sale (POS) Gratis">
    <meta property="og:description" content="Lakasir merupakan aplikasi point of sale (POS) yang memudahkan pengelolaan bisnis Anda. unduh secara gratis">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:image" content="{{ env('APP_URL') }}/assets/logo/image.png">
    <meta property="og:type" content="website">

    <!-- Twitter Card tags for Twitter -->
    <meta name="twitter:card" content="Lakasir - Aplikasi Point of Sale (POS) Gratis">
    <meta name="twitter:site" content="@yourtwitterhandle">
    <meta name="twitter:title" content="Lakasir - Aplikasi Point of Sale (POS) Gratis">
    <meta name="twitter:description" content="Lakasir merupakan aplikasi point of sale (POS) yang memudahkan pengelolaan bisnis Anda. unduh secara gratis">
    <meta name="twitter:image" content="{{ env('APP_URL') }}/assets/logo/image.png">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
  </head>
  <body>
    {{ $slot }}
    @livewireScripts
    @filamentScripts

  </body>
</html>


