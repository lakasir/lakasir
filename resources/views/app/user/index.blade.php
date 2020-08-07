@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.purchasings.title')" resources="purchasing">
    @slot('thead')
      <th> {{ __('app.purchasings.column.username') }} </th>
      <th> {{ __('app.purchasings.column.email') }} </th>
      <th> {{ __('app.purchasings.column.role') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#purchasing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route($resources.'.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'username', name: 'username', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'email', name: 'email' },
          { data: 'role', name: 'role' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
