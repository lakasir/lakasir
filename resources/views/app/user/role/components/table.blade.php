<x-components-index-table :title="__('app.role.title')" :resources="$resources">
  @slot('thead')
    <th> {{ __('app.role.column.name') }} </th>
    <th> {{ __('app.role.column.guard_name') }} </th>
  @endslot
</x-components-index-table>

@push(config('hascrudactions.wrapper.javascript'))
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
          { data: 'guard_name', name: 'guard_name' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
