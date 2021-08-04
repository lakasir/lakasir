@extends('adminlte::page')

@section('content')
  <x-index-table :title="__('app.customer_types.title')" :resources="$resources">
    @slot('topDiv')
      <x-bulk-action>
      </x-bulk-action>
    @endslot
    @slot('content')
      {{ $dataTable->table() }}
    @endslot
  </x-index-table>
@endsection

@push('js')
  {{$dataTable->scripts()}}
@endpush
