@extends('adminlte::page')

@section('content')
  @include('app.master.customers.components.form', [
    'route' => route('customer.update', $data),
    'data' => $data,
    'method' => 'PUT'
  ])
@endsection
