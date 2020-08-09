@extends('adminlte::page')

@section('content')
  @include('app.user.components.form', [
    'route' => route('user.store'),
    'title' => __('app.user.create.title')
  ])
@endsection
