@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>
  <x-index-table :title="__('app.groups.title')" resources="group">
    @slot('thead')
      <th> {{ __('app.groups.column.name') }} </th>
      <th> {{ __('app.groups.column.total_customer') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#group-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('group.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'customers_count', name: 'customers_count'},
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
