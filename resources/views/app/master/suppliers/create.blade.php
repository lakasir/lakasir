@extends('adminlte::page')

@section('content')
  @include('app.master.suppliers.components.form', [
    'route' => route('supplier.store')
  ])
@endsection
