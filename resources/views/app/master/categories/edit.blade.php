@extends('adminlte::page')

@section('content')
  @include('app.master.categories.components.form', [
    'route' => route('category.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.categories.edit.title')
  ])
@endsection
