@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>
  <x-index-table :title="__('app.customer_types.title')" resources="customer_type">
    @slot('thead')
      <th> {{ __('app.customer_types.column.name') }} </th>
      <th> {{ __('app.customer_types.column.default_point') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#customer_type-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('customer_type.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'default_point', name: 'default_point' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
