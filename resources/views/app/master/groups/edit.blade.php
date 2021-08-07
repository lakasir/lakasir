@extends('adminlte::page')

@section('content')
  {{ Breadcrumbs::render("{$resources}.edit", $data) }}
  @include('app.master.groups.components.form', [
    'route' => route('group.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.groups.edit.title')
  ])
@endsection
