@extends('adminlte::page')
@push('css')
  <style>
  .btn-select-supplier {
    width: 100%;
    height: 100%;
    border-radius: 15px;
    max-width: 50%;
    background-color: white;
  }
  .btn-select-supplier:hover {
    background-color: #E9ECEF;
  }
  </style>
@endpush

@section('content')
  <div class="container-fluid content-layout mt--6" style="max-width: 1300px !important;">
    {{ Breadcrumbs::render("{$resources}.create") }}
    @include('app.transaction.purchasings.components.form', [
      'route' => route('purchasing.store'),
      'title' => __('app.purchasings.create.title')
    ])
  </div>
@endsection
