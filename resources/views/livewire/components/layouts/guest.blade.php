<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
  </head>
  <body>
    {{ $slot }}
    @livewireScripts
  </body>
</html>


