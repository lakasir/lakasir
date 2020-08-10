@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>

  <x-index-table :title="__('app.suppliers.title')" resources="supplier">
    @slot('thead')
      <th> {{ __('app.suppliers.column.name') }} </th>
      <th> {{ __('app.suppliers.column.code') }} </th>
      <th> {{ __('app.suppliers.column.shop_name') }} </th>
      <th> {{ __('app.suppliers.column.phone') }} </th>
      <th> {{ __('app.suppliers.column.address') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#supplier-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('supplier.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'code', name: 'code' },
          { data: 'shop_name', name: 'shop_name' },
          { data: 'phone', name: 'phone' },
          { data: 'address', name: 'address' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
