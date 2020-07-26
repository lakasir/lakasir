@extends('adminlte::page')

@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('app.categorys.title') }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="categories-table">
          <thead>
            <tr>
              <th>#</th>
              <th> {{ __('app.categories.column.name') }} </th>
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
      $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('category.index') !!}',
        columns: [
          { data: 'DT_RowIndex', name: '#' },
          { data: 'name', name: 'name' },
        ]
      });
    });
  </script>
@endpush
