@extends('adminlte::page')

@section('content')
  @include('app.transaction.sellings.components.card')
  <x-index-table :title="__('app.'.Str::plural($resources).'.title.index')" :resources="$resources"
                                                                                  :withoutaction="true" :withoutcheckbox="true">
    @slot('thead')
      <th> {{ __('app.sellings.column.transaction_number') }} </th>
      <th> {{ __('app.sellings.column.user') }} </th>
      <th> {{ __('app.sellings.column.date') }} </th>
      <th> {{ __('app.sellings.column.payment_method') }} </th>
      <th> {{ __('app.sellings.column.total_price') }} </th>
      <th> {{ __('app.sellings.column.total_profit') }} </th>
      <th> {{ __('app.sellings.column.money') }} </th>
      <th> {{ __('app.sellings.column.refund') }} </th>
      <th> {{ __('app.sellings.column.total_qty') }} </th>
    @endslot
  </x-index-table>
  <x-modal id="modal-selling-detail"
           :title="__('app.'.Str::plural($resources).'.title.detail')"
           size="lg">
    @include('app.transaction.sellings.components.detail')
  </x-modal>
@endsection

@push('js')
  <script>
    $(function() {
      let tableId = '#{{ $resources.'-table' }}';
      $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route($resources.'.index') !!}',
        columns: [
          // { data: 'checkbox', name: '#', orderable: false, searchable: false, width: '3%' },
          { data: 'number_transaction', name: 'number_transaction', render: function ( data, type, row ) {
            return '<button class="btn btn-link" id=get-detail-purchasing data-url='+ route('{{ $resources }}.show', row) +'>'+data+'</button>'
          }},
          { data: 'user', name: 'User' },
          { data: 'transaction_date', name: 'Transaction Date' },
          { data: 'payment_method', name: 'Payment Method' },
          { data: 'total_price', name: 'Total Price' },
          { data: 'total_profit', name: 'Total Profit' },
          { data: 'money', name: 'money' },
          { data: 'refund', name: 'Refund' },
          { data: 'total_qty', name: 'Total Qty' },
          { data: 'created_at', name: 'Created At' },
        ]
      })
      $(tableId).on('click', '#get-detail-purchasing', function(event) {
        console.log(helpers.priceFormat(2132312))
        $.ajax({
          url: $(event.target).data('url'),
          success: function(data) {
            let tr = '';
            data.payload.map(function(el) {
              // console.log(el)
              tr += `<tr><td>${el.item_name}</td><td>${el.qty}</td><td>${helpers.priceFormat(el.price)}</td><td>${helpers.priceFormat(el.profit)}</td></tr>`
            })
            $('#detail-selling-table tbody').html(tr)
            $('#modal-selling-detail').modal('show')
          }
        })
      })
    });
  </script>
@endpush
