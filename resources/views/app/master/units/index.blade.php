@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route('unit.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>

  <div class="card">
    <div class="card-header">
      {{ __('app.units.title') }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="units-table">
          <thead>
            <tr>
              <th>#</th>
              <th> {{ __('app.units.column.name') }} </th>
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
      $('#units-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('unit.index') !!}',
        columns: [
          { data: 'DT_RowIndex', name: '#' },
          { data: 'name', name: 'name' },
        ]
      });
    });
  </script>
@endpush
