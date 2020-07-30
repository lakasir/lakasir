@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.customers.title')" resources="customer">
    @slot('thead')
      <th> {{ __('app.customers.column.name') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('customer.index') !!}',
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
