@extends('adminlte::page')

@section('content')
  <div class="d-flex justify-content-center">
    <div class="card col-md-10">
      <div class="card-header">
        <h5 class="card-title">{{ $data->invoice_number }}</h5>
        @if (app()->environment(['local', 'staging']))
          <div class="card-action float-right">
            <button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-pen"></i></button>
          </div>
        @endif
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <label>{{ __('app.purchasings.column.payment_method') }}</label>
            <p class="card-text">{{ dash_to_space($data->payment_method) }}</p>
          </div>
          <div class="col-md-6">
            <label>{{ __('app.purchasings.column.paid') }}</label>
            <p class="card-text">{{ $data->paid }}</p>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label>{{ __('app.purchasings.column.note') }}</label>
            <p class="card-text">{{ $data->note ?? __('app.purchasings.note.nothing_note') }}</p>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6">
            <label>{{ __('app.purchasings.column.date') }}</label>
            <p class="card-text">{{ $data->date }}</p>
          </div>
          <div class="col-md-6">
            <label>{{ __('app.purchasings.column.supplier') }}</label>
            <p class="card-text">{{ optional($data->supplier ?? '')->name }}</p>
          </div>
        </div>
        <br>
        @include('app.transaction.purchasings.components.table_detail_item')
      </div>
    </div>
  </div>
@endsection
