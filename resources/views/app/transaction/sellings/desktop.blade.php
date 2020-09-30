@extends('adminlte::page')

@section('adminlte_css')
<style>
.scrollable {
  overflow-y: scroll;
  min-width: 600px;
  height: 600px;
}
</style>
@endsection

@section('content')
  <cashier-app token="{{ $token }}"></cashier-app>
@endsection

@push('js')

@endpush

