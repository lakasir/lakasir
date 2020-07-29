@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.items.title')" resources="item">
    @slot('thead')
      <th> {{ __('app.items.column.name') }} </th>
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
      $('#item-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('item.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name' },
          { data: 'internal_production', name: 'internal_production' },
          { data: 'category_name', name: 'category_name' },
          { data: 'unit_name', name: 'unit_name' },
          { data: 'initial_price', name: 'initial_price' },
          { data: 'selling_price', name: 'selling_price' },
          { data: 'created_at', name: 'Created At' }
        ]
      });
    });
  </script>
@endpush
