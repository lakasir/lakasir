@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.edit", $data) }}
  @include('app.master.customers.components.form', [
    'route' => route('customer.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.customers.edit.title')
  ])
@endsection
