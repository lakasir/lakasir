@extends('adminlte::page')

@section('content')
  @include('app.master.customers.components.form', [
    'route' => route('customer.store'),
  ])
@endsection
