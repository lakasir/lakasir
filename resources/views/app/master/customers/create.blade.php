@extends('adminlte::page')

@section('content')
  @include('app.master.customers.components.form', [
    'route' => route('customer.store'),
    'title' => __('app.customers.create.title')
  ])
@endsection
