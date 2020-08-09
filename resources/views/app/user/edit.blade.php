@extends('adminlte::page')

@section('content')
  @include('app.user.components.form', [
    'route' => route('user.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.user.edit.title')
  ])
@endsection
