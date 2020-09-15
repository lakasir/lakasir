@extends('adminlte::page')

@section('content')
  @include('app.master.payment_methods.components.form', [
    'route' => route('payment_method.store'),
    'title' => __('app.payment_methods.create.title')
  ])
@endsection
