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
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name' },
          { data: 'created_at', name: 'Created At' }
        ]
      });
    });
  </script>
@endpush
