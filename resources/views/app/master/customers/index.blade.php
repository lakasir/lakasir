@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>
  <x-index-table :title="__('app.customers.title')" resources="customer">
    @slot('thead')
      <th> {{ __('app.customers.column.name') }} </th>
      <th> {{ __('app.customers.column.email') }} </th>
      <th> {{ __('app.customers.column.code') }} </th>
      <th> {{ __('app.customers.column.total_point') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('customer.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'code', name: 'code' },
          { data: 'total_point', name: 'total_point' },
          { data: 'created_at', name: 'Created At' }
        ]
      });
      $('#{{ $resources }}-table tbody').on('click', 'tr td', function () {
        let id = $(this).parent().attr('id')
        if($(this)[0].className != 'sorting_1') {
          window.location.href = route('{{ $resources }}'+'.edit', id)
        }
      });
    });
  </script>
@endpush
