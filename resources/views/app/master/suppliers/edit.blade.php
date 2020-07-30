@extends('adminlte::page')

@section('content')
  @include('app.master.suppliers.components.form', [
    'route' => route('supplier.update', $data),
    'data' => $data,
    'method' => 'PUT'
  ])
@endsection
