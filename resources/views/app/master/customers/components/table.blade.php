<x-components-index-table :title="__('app.customers.title')" :resources="$resources">
  @slot('thead')
    <th> {{ __('app.customers.column.name') }} </th>
    <th> {{ __('app.customers.column.email') }} </th>
    <th> {{ __('app.customers.column.code') }} </th>
    <th> {{ __('app.customers.column.total_point') }} </th>
  @endslot
</x-components-index-table>

@push(config('hascrudactions.wrapper.javascript'))
  <script>
    $(function() {
      $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('customer.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'email', name: 'email' },
          { data: 'code', name: 'code' },
          { data: 'total_point', name: 'total_point', searchable: false },
          { data: 'created_at', name: 'Created At', searchable: false },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
