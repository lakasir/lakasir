@extends('adminlte::page')

@section('content')
  <div class="container-fluid content-layout mt--6" style="max-width: 1300px !important;">
    {{ Breadcrumbs::render("{$resources}.edit", $data) }}
    @include('app.master.items.components.form', [
      'route' => route('item.update', $data),
      'method' => 'PUT',
      'title' => __('app.items.edit.title', ['title' => null])
      ])
  </div>
@endsection
