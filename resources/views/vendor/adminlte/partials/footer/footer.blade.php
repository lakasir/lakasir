<footer class="main-footer">
    {{-- @yield('footer') --}}
    <div class="float-right d-none d-sm-block">
      <b> {{ __('lakasir.version') }}</b> {{ __('lakasir.versioning') }}
    </div>
    <strong> {{ __('lakasir.supported') }} <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>.</strong>  {{ __('lakasir.free_pos_software') }}
</footer>
