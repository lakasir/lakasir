@extends('adminlte::master')

@section('classes_body', 'hold-transition register-page')

@section('body')
  <div class="row">
    <div class="col">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <p class="card-text"> {{ __('app.completed.message') }}</p>
        </div>
        <div class="card-footer">
          <a href="{{ route('login') }}"> {{ __('app.completed.link') }}</a>
        </div>
      </div>
    </div>
  </div>
@endsection
