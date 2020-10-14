@extends('adminlte::master')

@section('classes_body', 'hold-transition login-page')

@section('body')
<div class="login-box">
  <div class="login-logo">
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">
        <img src="{{ url(config('lakasir.logo-sm')) }}" alt="" width="50" height="50"/>
      </p>
      @include('app.auth.components.form')
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
  <strong> {{ __('lakasir.supported') }} <a href="{{ config('app.url') }}">{{ config('lakasir.appname') }}</a>.</strong>  {{ __('lakasir.free_pos_software') }}
</div>
<!-- /.login-box -->
@endsection
