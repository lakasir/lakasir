@extends('adminlte::page')

@section('content')
  @include('app.master.categories.components.form', [
    'route' => route('category.store')
  ])
@endsection
