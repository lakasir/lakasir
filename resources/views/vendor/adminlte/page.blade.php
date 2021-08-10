@extends('adminlte::master')

@inject('layoutHelper', \JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper)

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            <div class="content-header">
                <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                    @yield('content_header')
                </div>
            </div>

            {{-- Main Content --}}
            <div class="content">
                <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
                  @if(flash()->message)
                    <div class="">
                      <div class="alert {{ flash()->class }} alert-dismissible fade show" role="alert">
                        <strong>{{ __('app.global.'.flash()->level) }}</strong> {{ flash()->message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    </div>
                  @endif
                  @if (setting('breadcrumb'))
                    @yield('breadcrumb')
                  @endif
                  @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @include('adminlte::partials.footer.footer')

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
          @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
  @stop

  @section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script charset="utf-8">
      (function ($, DataTable) {
        "use strict";
          DataTable.ext.buttons.bulkDelete = {
          className: 'buttons-bulk-delete',

          action: function (e, dt, button, config) {
            let checkbox = $(`input[type=checkbox].${config.idTarget}:checked`);
            if (checkbox.length == 0) {
              let warning = 'Sorry, there is no data you selected!';
              if (config.warning) {
                warning = config.warning;
              }
              alert(dt.i18n('app.global.warning.checked_first', warning));
              return;
            }
            let confirmQuestion = 'Are you sure you want to mass delete?';
            if (config.confirm) {
              confirmQuestion = config.confirm
            }
            if (confirm(confirmQuestion)) {
              for (let i = 0, len = checkbox.length; i < len; i++) {
                $('form#form-bulk-delete').append(
                  $("<input/>", {
                    id: 'row-selected',
                    name: 'ids[]',
                    type: 'hidden',
                    value: $(checkbox[i]).val()
                  })
                )
              }
              $('form#form-bulk-delete').attr('action', config.url).submit()
            }
            return;
          }
        };

        DataTable.ext.buttons.bulkAction = {
          className: 'buttons-bulk-action',

          action: function (e, dt, button, config) {
            console.log(config, e, dt, button);
            // window.location = window.location.href.replace(/\/+$/, "") + '/create';
          }
        };
      })(jQuery, jQuery.fn.dataTable);
    </script>
    @stack('js')
    @yield('js')
  @stop
