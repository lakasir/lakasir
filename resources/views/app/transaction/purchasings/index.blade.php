@extends('adminlte::page')

@section('content')
  @include('app.transaction.purchasings.components.card')
  <div class="row col">
    <v-button float="right" text="{{ __('app.global.create') }}" to="{{ route($resources.'.create') }}" icon="fas fa-plus"></v-button>
  </div>
  <br>

  <x-index-table :title="__('app.purchasings.title')" resources="purchasing">
    @slot('thead')
      <th> {{ __('app.purchasings.column.invoice_number') }} </th>
      <th> {{ __('app.purchasings.column.user') }} </th>
      <th> {{ __('app.purchasings.column.date') }} </th>
      <th> {{ __('app.purchasings.column.payment_method') }} </th>
      <th> {{ __('app.purchasings.column.total_initial_price') }} </th>
      <th> {{ __('app.purchasings.column.total_selling_price') }} </th>
      <th> {{ __('app.purchasings.column.total_qty') }} </th>
      <th> {{ __('app.purchasings.column.paid') }} </th>
    @endslot
  </x-index-table>
@endsection

@push('js')
  <script>
    $(function() {
      $('#purchasing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('purchasing.index', [
          'filter' => [
            'key' => request('filter.key'),
            'value' => request('filter.value')
          ]
        ]) !!}',
        columns: [
          { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'invoice_number', name: 'invoice_number', render: function ( data, type, row ) {
            return '<a href='+ route('{{ $resources }}.show', row) +'>'+data+'</a>'
          }},
          { data: 'user', name: 'User' },
          { data: 'date', name: 'Date' },
          { data: 'payment_method', name: 'Payment Method' },
          { data: 'total_initial_price', name: 'Total Initial Price' },
          { data: 'total_selling_price', name: 'Total Selling Price' },
          { data: 'total_qty', name: 'Total Qty' },
          { data: 'paid', name: 'Paid' },
          { data: 'created_at', name: 'Created At' },
          { data: 'action', name: 'action', orderable: false, searchable: false, width: '3%' },
        ]
      });
    });
  </script>
@endpush
