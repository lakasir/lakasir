@extends('adminlte::page')

@section('content')
  @include('app.master.groups.components.form', [
    'route' => route('group.store'),
    'title' => __('app.groups.create.title')
  ])
@endsection
