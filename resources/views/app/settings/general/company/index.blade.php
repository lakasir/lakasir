@extends('adminlte::page')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('s.general.index') }}">{{ __('menu.settings') }}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ __('app.settings.general.company.title') }}</li>
    </ol>
  </nav>
@endsection

@section('content')
  @include('app.settings.general.company.components.form')
@endsection

