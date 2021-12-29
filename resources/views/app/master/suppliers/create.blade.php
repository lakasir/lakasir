@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.create") }}
  @include('app.master.suppliers.components.form', [
    'route' => route('supplier.store'),
    'title' => __('app.suppliers.create.title')
  ])
@endsection
