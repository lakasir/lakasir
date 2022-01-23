@extends('adminlte::page')

@section('content')
  <div class="container-fluid content-layout mt--6" style="max-width: 1300px !important;">
    {{ Breadcrumbs::render("{$resources}.create") }}
    @include('app.master.items.components.form', [
      'route' => route('item.store'),
      'title' => __('app.items.create.title')
    ])
  </div>
@endsection
