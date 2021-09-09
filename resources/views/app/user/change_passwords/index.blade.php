@extends('adminlte::page')
@section('content')
  {{ Breadcrumbs::render("{$resources}.change_password") }}
  <div class="row">
    <div class="col-md-3">
      @include('app.user.profiles.components.image')
      @include('app.user.profiles.components.about')
    </div>
    <div class="col-md 9">
      <div class="card">
        <div class="card-body">
          {{ Builder::make('change-password-form')->setRoute(route('change_password.store'), $method ?? null)->render() }}
        </div>
      </div>
    </div>
  </div>
@endsection
