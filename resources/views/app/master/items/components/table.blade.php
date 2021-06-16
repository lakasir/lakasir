<div class="row col">
  <div class="mr-2">
    <a href="{{ route($resources.'.download-template') }}" class="btn btn-info"><i class="fas fa-download"></i><span class="mr-1"></span>{{ __('app.global.download', ['title' => 'Template']) }}</a>
  </div>

  <div class="mr-2">
    <v-button-upload float="right" name="item-import" to="{{ route($resources.'.import') }}" text="{{ __('app.global.import', ['title' => 'Item Template']) }}" color="info" icon="fas fa-upload"></v-button-upload>
  </div>
</div>
<br>

<x-components-index-table :title="__('app.items.title')" :resources="$resources">
  @slot('thead')
    <th> {{ __('app.items.column.name') }} </th>
    <th> {{ __('app.items.column.stock.last_stock') }} </th>
    <th> {{ __('app.items.column.internal_production') }} </th>
    <th> {{ __('app.items.column.category.name') }} </th>
    <th> {{ __('app.items.column.price.initial_price') }} </th>
    <th> {{ __('app.items.column.price.selling_price') }} </th>
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
          { data: 'last_stock', name: 'last_stock' },
          { data: 'internal_production', name: 'internal_production' },
          { data: 'category_name', name: 'category_name' },
          { data: 'initial_price', name: 'initial_price' },
          { data: 'selling_price', name: 'selling_price' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
