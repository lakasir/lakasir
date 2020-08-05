@extends('adminlte::master')

@section('classes_body', 'hold-transition register-page')

@section('body')
<div class="register-box">
  <div class="register-logo">
    <p> {{ env('APP_NAME') }} </p>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg"></p>
      @include('app.auth.components.form')
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
@endsection
