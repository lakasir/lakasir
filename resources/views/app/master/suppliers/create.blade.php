@extends('adminlte::page')

@section('content')
  <div class="container-fluid content-layout mt--6" style="max-width: 1300px !important;">
    {{ Breadcrumbs::render("{$resources}.create") }}
    @include('app.master.suppliers.components.form', [
      'route' => route('supplier.store'),
      'title' => __('app.suppliers.create.title')
      ])
  </div>
@endsection
