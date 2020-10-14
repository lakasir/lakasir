@extends('adminlte::page')

@section('css')
  <style type="text/css" media="screen">
    .btn-icon-clipboard {
      padding: 1.5rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.25;
      color: #32325d;
      background-color: white;
      border-radius: .375rem;
      border: 0;
      text-align: left;
      font-family: inherit;
      display: inline-block;
      vertical-align: middle;
      text-decoration: none;
      -moz-appearance: none;
      cursor: pointer;
      width: 100%;
      margin: .5rem 0;
    }
    .btn-icon-clipboard:hover {
      background-color: #f6f9fc;
    }
    .settings-icons {
      position: relative;
      top: 0.6rem;
      height: 45px;
      color: #32325d;
      background-color: #f6f9fc;
      width: 45px;
      padding-top: 10px;
      font-size: 1.5em;
    }
  </style>
@endsection

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ __('menu.settings') }}</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="card">
    {{-- <div class="card-header"> --}}
      {{--   {{ __('menu.general') }} --}}
      {{-- </div> --}}
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <a href="{{ route('s.general.company.index') }}">
            <button type="button" class="btn-icon-clipboard p-2">
              <div class="row mx-0">
                <div class="col-auto">
                  <div class="badge badge-secondary settings-icons">
                    <i class="fa fa-lg fa-building"></i>
                  </div>
                </div>
                <div class="col ml--2">
                  <h4 class="mb-0">{{ __('app.settings.general.company.title') }}</h4>
                  <p class="text-sm text-muted mb-0">{{ __('app.settings.general.company.description') }}</p>
                </div>
              </div>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
@endpush
