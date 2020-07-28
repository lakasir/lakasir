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
          { data: 'DT_RowIndex', name: '#' },
          { data: 'name', name: 'name' },
        ]
      });
    });
  </script>
@endpush
