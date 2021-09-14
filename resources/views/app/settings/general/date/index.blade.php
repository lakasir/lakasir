@extends('adminlte::page')

@section('content')
  <x-index-table>
    @slot('topDiv')
      {{ Breadcrumbs::render("{$resources}.index") }}
    @endslot
    @slot('content')
    @endslot
  </x-index-table>
@endsection

@push('js')
@endpush

