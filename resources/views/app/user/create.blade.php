@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.create") }}
  @include('app.user.components.form', [
    'route' => route('user.store'),
    'title' => __('app.user.create.title')
  ])
@endsection
