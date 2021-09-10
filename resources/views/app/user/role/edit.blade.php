@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.edit", $data) }}
  @include('app.user.role.components.form', [
    'route' => route('role.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.role.edit.title')
  ])
@endsection
