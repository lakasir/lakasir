@extends('adminlte::page')

@section('content')
  <x-index-table>
    @slot('topDiv')
      {{ Breadcrumbs::render("{$resources}.index") }}
    @endslot
    @slot('content')
      {{ $dataTable->table() }}
    @endslot
  </x-index-table>
  @can('update-user')
    @include('app.user.components.modals.assign_role')
  @endcan
@endsection

@push('js')
  {{$dataTable->scripts()}}
  <script charset="utf-8">
    $(document).ready(function() {
      $(".select2").select2({ width: '100%' });
      $('#datatable').on('click', '.action-assign-role', function(event) {
        event.preventDefault();
        let user_id = $(event.target).data('id');
        let key = $(event.target).data('key');
        let routes = $(event.target).data('routes') + "?key-bypass-update=" + key;
        let role = $(event.target).data('role');
        $('#assign-role-modal').modal('show');
        console.log($('form.form-assign-role'))
        $('form.form-assign-role').attr("action", routes);
      })
    })
  </script>
@endpush

