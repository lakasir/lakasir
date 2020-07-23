@extends('adminlte::page')

@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('app.groups.title') }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="groups-table">
          <thead>
            <tr>
              <th>#</th>
              <th> {{ __('app.groups.column.name') }} </th>
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
      $('#groups-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('group.index') !!}',
        columns: [
          { data: 'id', name: '#' },
          { data: 'name', name: 'name' },
        ]
      });
    });
  </script>
@endpush
