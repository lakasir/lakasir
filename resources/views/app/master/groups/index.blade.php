@extends('adminlte::page')

@section('content')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>
  <x-index-table :title="__('app.groups.title')" resources="group">
    @slot('thead')
      <th> {{ __('app.groups.column.name') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#group-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('group.index') !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'name', name: 'name' },
          { data: 'created_at', name: 'Created At' }
        ]
      });
      $('#{{ $resources }}-table tbody').on('click', 'tr td', function () {
        let id = $(this).parent().attr('id')
        if($(this)[0].className != 'sorting_1') {
          window.location.href = route('{{ $resources }}'+'.edit', id)
        }
      });
    });
  </script>
@endpush
