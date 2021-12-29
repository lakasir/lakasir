@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render('customer_type.create') }}
  @include('app.master.customer_types.components.form', [
    'route' => route('customer_type.store', request()->input()),
    'title' => __('app.customer_types.create.title')
  ])
@endsection
