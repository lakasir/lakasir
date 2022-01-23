@extends('adminlte::page')

@section('content')
  <div class="container-fluid content-layout mt--6" style="max-width: 1300px !important;">
    {{ Breadcrumbs::render("{$resources}.edit", $data) }}
    @include('app.master.suppliers.components.form', [
      'route' => route('supplier.update', $data),
      'data' => $data,
      'method' => 'PUT',
      'title' => __('app.suppliers.edit.title')
      ])
  </div>
@endsection
