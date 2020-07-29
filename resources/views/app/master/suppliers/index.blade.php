@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.suppliers.title')" resources="supplier">
    @slot('thead')
      <th> {{ __('app.suppliers.column.name') }} </th>
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
          { data: 'name', name: 'name' },
          { data: 'created_at', name: 'Created At' }
        ]
      });
    });
  </script>
@endpush
