@extends('adminlte::page')

@section('content')
  @include('app.master.items.components.form', [
    'route' => route('item.store'),
    'title' => __('app.items.create.title')
  ])
@endsection
