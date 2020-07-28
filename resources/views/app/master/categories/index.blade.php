@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route('category.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>
  <x-index-table :title="__('app.categories.title')" resources="category">
    @slot('thead')
      <th> {{ __('app.categories.column.name') }}</th>
    @endslot
  </x-index-table>

@endsection

@push('js')
  <script>
    $(function() {
      $('#category-table').DataTable({
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
