@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.groups.title')" resources="group">
    @slot('thead')
      <th> {{ __('app.groups.column.name') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#group-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('group.index') !!}',
        columns: [
          { data: 'DT_RowIndex', name: '#' },
          { data: 'name', name: 'name' },
        ]
      });
    });
  </script>
@endpush
