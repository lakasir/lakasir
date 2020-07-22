@extends('adminlte::page')

@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('app.categories.title') }}
    </div>
    <div class="card-body">
      {!! $html->table(['class' => 'table']) !!}
    </div>
    <div class="card-footer">
    </div>
  </div>
@endsection

@push('js')
  {!! $html->scripts() !!}
@endpush

