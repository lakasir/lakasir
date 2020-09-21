@extends('adminlte::page')

@section('adminlte_css')
<style>
.scrollable {
  overflow-y: scroll;
  min-width: 400px;
  height: 400px;
}
</style>
@endsection

@section('content')
  <cashier-app token="{{ $token }}"></cashier-app>
@endsection

@push('js')

@endpush

