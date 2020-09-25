@extends('adminlte::page')

@section('content')
  @include('app.master.customer_types.components.form', [
    'route' => route('customer_type.store'),
    'title' => __('app.customer_types.create.title')
  ])
@endsection
