@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route('unit.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>

  <x-index-table :title="__('app.units.title')" resources="unit">
    @slot('thead')
      <th> {{ __('app.units.column.name') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#unit-table').DataTable({
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
