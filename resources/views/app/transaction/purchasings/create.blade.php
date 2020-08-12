@extends('adminlte::page')

@section('content')
  @include('app.transaction.purchasings.components.form', [
    'route' => route('purchasing.store'),
    'title' => __('app.purchasings.create.title')
  ])
@endsection
