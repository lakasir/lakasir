@extends('adminlte::master')

@section('classes_body', 'hold-transition register-page')

@section('body')
<div class="register-box">
  <div class="register-logo">
    <p> {{ env('APP_NAME') }} </p>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <ul class="nav nav-pills">
        <li class="nav-item col text-center">
          <a class="nav-link {{ request()->tab == 'database' ? 'active' : '' }}" href="?tab=database">{{ __('app.install.tab.database') }}</a>
        </li>
        <li class="nav-item col text-center">
          <a class="nav-link {{ request()->tab == 'user' ? 'active' : '' }}" href="?tab=user">{{ __('app.install.tab.user') }}</a>
        </li>
        <li class="nav-item col text-center">
          <a class="nav-link {{ request()->tab == 'company' ? 'active' : '' }}" href="?tab=company">{{ __('app.install.tab.company') }}</a>
        </li>
      </ul>

      <p class="login-box-msg"></p>

      @include('app.install.components.' . request()->tab)

    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
@endsection
