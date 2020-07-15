@extends('adminlte::page')

@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('app.items.title') }}
    </div>
    <div class="card-body">
      <table class="table datatable md1-data-table dataTable" cellspacing="0">
        <thead>
          <tr>
            <th>#</th>
            <th> {{ __('app.items.column.name') }}</th>
            <th> {{ __('app.items.column.internal_production') }}</th>
            <th> {{ __('app.items.column.category.name') }}</th>
            <th> {{ __('app.items.column.unit.name') }}</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="card-footer">
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
        $('.datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ route('item.index') }}',
          columns: [
            {data: 'DT_RowIndex'},
            {data: 'name'},
            {data: 'internal_production'},
            {data: 'category_name'},
            {data: 'unit_name'}
          ]
        });
    });
  </script>
@endpush
