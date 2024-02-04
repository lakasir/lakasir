<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="data:image/svg+xml,&lt;svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22&gt;&lt;text y=%22.9em%22 font-size=%2290%22&gt;🏎&lt;/text&gt;&lt;/svg&gt;">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
  </head>
  <body>
    {{ $slot }}
    @livewireScripts
  </body>
</html>


