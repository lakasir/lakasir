@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.create") }}
  @include('app.master.payment_methods.components.form', [
    'route' => route('payment_method.store'),
    'title' => __('app.payment_methods.create.title')
  ])
@endsection
