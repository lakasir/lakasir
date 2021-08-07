@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.create") }}
  @include('app.master.groups.components.form', [
    'route' => route('group.store'),
    'title' => __('app.groups.create.title')
  ])
@endsection
