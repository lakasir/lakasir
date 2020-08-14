@extends('adminlte::page')

@section('content')
  @include('app.master.items.components.form', [
    'route' => route('item.update', $data),
    'method' => 'PUT',
    'title' => __('app.items.edit.title')
  ])
@endsection
