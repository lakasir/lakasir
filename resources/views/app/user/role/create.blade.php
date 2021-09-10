@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.create") }}
  @include('app.user.role.components.form', [
    'route' => route('role.store'),
    'title' => __('app.role.create.title')
  ])
@endsection
