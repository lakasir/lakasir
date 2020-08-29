@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>

  <x-index-table :title="__('app.items.title')" :resources="$resources">
    @slot('thead')
      <th> {{ __('app.items.column.name') }} </th>
      <th> {{ __('app.items.column.stock.last_stock') }} </th>
      <th> {{ __('app.items.column.internal_production') }} </th>
      <th> {{ __('app.items.column.category.name') }} </th>
      <th> {{ __('app.items.column.unit.name') }} </th>
      <th> {{ __('app.items.column.price.initial_price') }} </th>
      <th> {{ __('app.items.column.price.selling_price') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#{{ $resources }}-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route($resources.'.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'last_stock', name: 'last_stock' },
          { data: 'internal_production', name: 'internal_production' },
          { data: 'category_name', name: 'category_name' },
          { data: 'unit_name', name: 'unit_name' },
          { data: 'initial_price', name: 'initial_price' },
          { data: 'selling_price', name: 'selling_price' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
