@extends('adminlte::page')

@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('app.purchasings.title') }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="categories-table">
          <thead>
            <tr>
              <th>#</th>
              <th> {{ __('app.purchasing.column.date') }} </th>
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
        ajax: '{!! route('purchasing.index') !!}',
        columns: [
          { data: 'DT_RowIndex', name: '#' },
          { data: 'date', name: 'date' },
        ]
      });
    });
  </script>
@endpush
