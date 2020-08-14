@extends('adminlte::page')
@section('content')
  <div class="row">
    <div class="col-md-3">
      @include('app.user.profiles.components.image')
      @include('app.user.profiles.components.about')
    </div>
    <div class="col-md 9">
    <x-form :route="route('change_password.store')" :title="__('app.user.change_password.update')" size="12">
      <v-input icon="fa-signature"
               placeholder="{{ __('app.user.change_password.placeholder.old_password') }}"
               label="{{ __('app.user.change_password.column.old_password') }}"
               old="{{ old('old_password') }}"
               @error('old_password')
               error-message="{{ $message }}"
               :error="true"
               @enderror
               name="old_password"
               type="password"
               :validation="['required']"
               ></v-input>
      <hr>
      <v-input icon="fa-signature"
               placeholder="{{ __('app.user.change_password.placeholder.new_password') }}"
               label="{{ __('app.user.change_password.column.new_password') }}"
               old="{{ old('new_password') }}"
               @error('new_password')
               error-message="{{ $message }}"
               :error="true"
               @enderror
               name="new_password"
               :validation="['required']"
               type="password"
               ></v-input>

      <v-input icon="fa-signature"
               placeholder="{{ __('app.user.change_password.placeholder.new_password_confirmation') }}"
               label="{{ __('app.user.change_password.column.new_password_confirmation') }}"
               old="{{ old('new_password_confirmation') }}"
               @error('new_password')
               error-message="{{ $message }}"
               :error="true"
               @enderror
               name="new_password_confirmation"
               :validation="['required']"
               type="password"
               ></v-input>
    </x-form>
    </div>
  </div>
@endsection
