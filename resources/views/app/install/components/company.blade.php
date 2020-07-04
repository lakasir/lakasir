<form action="{{ route('install.companyStore') }}" method="post">
  @csrf
  <v-dropdown icon="fa-building"
           label="{{ __('app.install.placeholder.company.business_type') }}"
           old="{{ old('business_type') }}"
           v-bind:lists='@json(config('array_options.business_type'))'
           @error('business_type') :error="true"
           error-message="{{ $message }}"
           @enderror
           :prepend="true" name="business_type"
           :validation="['required']"
  ></v-dropdown>
  <v-input icon="fa-building"
           placeholder="{{ __('app.install.placeholder.company.business_description') }}"
           :prepend="true"
           name="business_description" :validation="['required']"
           old="{{ old('business_description') }}"
           @error('business_description') :error="true"
           error-message="{{ $message }}"
           @enderror
           ></v-input>
  <div class="row">
    <div class="col-8">
    </div>
    <!-- /.col -->
    <div class="col-4">
      <button type="submit" class="btn btn-primary btn-block"> {{ __('app.install.submit') }} <i class="fas fa-check"></i></button>
    </div>
    <!-- /.col -->
  </div>
</form>

