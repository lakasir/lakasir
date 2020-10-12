<footer class="main-footer">
    {{-- @yield('footer') --}}
    <div class="float-right d-none d-sm-block">
      <b> {{ __('lakasir.version') }}</b> {{ config('lakasir.version') }}
    </div>
    <strong> {{ __('lakasir.supported') }} <a href="{{ config('app.url') }}">{{ config('lakasir.appname') }}</a>.</strong>  {{ __('lakasir.free_pos_software') }}
</footer>
