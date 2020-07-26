@extends('adminlte::page')

@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('app.items.title') }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="products-table">
          <thead>
            <tr>
              <th>#</th>
              <th> {{ __('app.items.column.name') }} </th>
              <th> {{ __('app.items.column.internal_production') }} </th>
              <th> {{ __('app.items.column.category.name') }} </th>
              <th> {{ __('app.items.column.unit.name') }} </th>
              <th> {{ __('app.items.column.price.initial_price') }} </th>
              <th> {{ __('app.items.column.price.selling_price') }} </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <div class="card-footer">
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(function() {
      $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('item.index') !!}',
        columns: [
          { data: 'DT_RowIndex', name: '#' },
          { data: 'name', name: 'name' },
          { data: 'internal_production', name: 'internal_production' },
          { data: 'category_name', name: 'category_name' },
          { data: 'unit_name', name: 'unit_name' },
          { data: 'initial_price', name: 'initial_price' },
          { data: 'selling_price', name: 'selling_price' },
        ]
      });
    });
  </script>
@endpush
