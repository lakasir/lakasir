@extends('adminlte::page')

@section('content')
  @include('app.transaction.purchasings.components.form', [
    'route' => route('purchasing.update', $data),
    'title' => __('app.purchasings.edit.title'),
    'method' => 'PUT'
  ])
@endsection
