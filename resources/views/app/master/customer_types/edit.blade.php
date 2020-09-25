@extends('adminlte::page')

@section('content')
  @include('app.master.customer_types.components.form', [
    'route' => route('customer_type.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.customer_types.edit.title')
  ])
@endsection
