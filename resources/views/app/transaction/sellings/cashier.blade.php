<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_token" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    <script>
      window._locale = '{{ app()->getLocale() }}';
      window._translations = {!! cache('translations') !!};
    </script>
  </head>
  <body>
    <div id="app">
    </div>
      <script src="{{ mix('js/vuetify/app.js') }}"></script>
      @routes
  </body>
</html>
