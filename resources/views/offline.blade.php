<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Offline - {{ config('app.name') }}</title>
    <link href="{{ asset('css/app/custom-stylesheet.css') }}" rel="stylesheet" data-navigate-track>
  </head>
  <body>
    <h1>You are currently not connected to any networks</h1>
    <script src="/livewire/livewire.js"   data-csrf="{{ csrf_token() }}" data-update-uri="/livewire/update" data-navigate-once="true"></script>
    <script src="{{ asset('js/app/indexeddb.js') }}"></script>
  </body>
</html>
