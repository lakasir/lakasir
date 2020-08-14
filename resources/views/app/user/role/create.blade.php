@extends('adminlte::page')

@section('content')
  @include('app.user.role.components.form', [
    'route' => route('role.store'),
    'title' => __('app.role.create.title')
  ])
@endsection
