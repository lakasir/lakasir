@extends('adminlte::page')

@section('content')
  @include('app.master.units.components.form', [
    'route' => route('unit.store'),
    'title' => __('app.units.create.title')
  ])
@endsection
