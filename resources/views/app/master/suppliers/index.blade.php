@extends('adminlte::page')

@section('content')
  <div class="row col">
    <div class="mr-2">
      <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
    </div>
    <div class="mr-2">
      <a href="{{ route($resources.'.download-template') }}" class="btn btn-info"><i class="fas fa-download"></i><span class="mr-1"></span>{{ __('app.global.download', ['title' => 'Template']) }}</a>
    </div>

    <div class="mr-2">
      <v-button-upload float="right" name="{{ $resources }}-import" to="{{ route($resources.'.import') }}" text="{{ __('app.global.import', ['title' => __('app.suppliers.title').' Template']) }}" color="info" icon="fas fa-upload"></v-button-upload>
    </div>
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
