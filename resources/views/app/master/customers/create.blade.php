@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.create") }}
  @include('app.master.customers.components.form', [
    'route' => route('customer.store'),
    'title' => __('app.customers.create.title')
  ])
@endsection
