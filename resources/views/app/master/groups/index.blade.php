@extends('adminlte::page')

@section('content')
  <x-index-table>
    @slot('topDiv')
      {{ Breadcrumbs::render("{$resources}.index") }}
    @endslot
    @slot('content')
      {{ $dataTable->table() }}
    @endslot
  </x-index-table>
@endsection

@push('js')
  {{$dataTable->scripts()}}
@endpush

