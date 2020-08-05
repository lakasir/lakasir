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
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'date', name: 'date' },
          { data: 'created_at', name: 'Created At' }
        ]
      });
    });
  </script>
@endpush
