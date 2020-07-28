@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.purchasings.title')" resources="purchasing">
    @slot('thead')
      <th> {{ __('app.purchasings.column.date') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#purchasing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('purchasing.index') !!}',
        columns: [
          { data: 'DT_RowIndex', name: '#' },
          { data: 'date', name: 'date' },
        ]
      });
    });
  </script>
@endpush
