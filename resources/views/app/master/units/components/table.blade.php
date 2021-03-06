<x-components-index-table :title="__('app.units.title')" :resources="$resources">
  @slot('thead')
    <th> {{ __('app.units.column.name') }} </th>
  @endslot
</x-components-index-table>

@push(config('hascrudactions.wrapper.javascript'))
  <script>
    $(function() {
      $('#unit-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('unit.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.edit', row) +'>'+data+'</a>'
          }},
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush


