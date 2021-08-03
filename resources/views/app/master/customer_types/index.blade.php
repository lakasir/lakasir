@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.customer_types.title')" :resources="$resources">
    @slot('topDiv')
      <a class="btn btn-primary my-2"
         href="{{ route('customer_type.create') }}"
         role="button"
         >@lang('app.global.create', ['title' => __('app.customer_types.title')])
      </a>
      <x-bulk-action>
      </x-bulk-action>
    @endslot
    @slot('thead')
      <th> {{ __('app.customer_types.column.name') }} </th>
      <th> {{ __('app.customer_types.column.default_point') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#{{ $resources }}-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route($resources.'.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'default_point', name: 'default_point' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
