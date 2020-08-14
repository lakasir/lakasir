@extends('adminlte::page')

@section('content')
  @include('app.master.units.components.form', [
    'route' => route('unit.update', $data),
    'data' => $data,
    'method' => 'PUT',
    'title' => __('app.units.edit.title')
  ])
@endsection
