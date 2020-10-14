<x-form :route="route('s.general.company.store')" :title="__('app.companies.title')" size="12">
  <div class="row">
    <v-input
      placeholder="{{ __('app.companies.placeholder.name') }}"
      label="{{ __('app.companies.column.name') }}"
      old="{{ old('name') }}"
      @error('name')
      error-message="{{ $message }}"
      :error="true"
      @enderror
      name="name"
      :validation="['required']"
      default-value="{{ optional( $data )->name }}"
      size="4">
    </v-input>
    <v-input
      placeholder="{{ __('app.companies.placeholder.reg_number') }}"
      label="{{ __('app.companies.column.reg_number') }}"
      old="{{ old('reg_number') }}"
      @error('reg_number')
      error-message="{{ $message }}"
      :error="true"
      @enderror
      name="reg_number"
      :validation="[]"
      readonly
      default-value="{{ optional( $data ?? '' )->reg_number }}"
      info="{{ __('app.companies.info.reg_number') }}"
      size="4">
    </v-input>
    <v-input
      placeholder="{{ __('app.companies.placeholder.expected_employee') }}"
      label="{{ __('app.companies.column.expected_employee') }}"
      old="{{ old('expected_max_employee') }}"
      @error('expected_max_employee')
      error-message="{{ $message }}"
      :error="true"
      @enderror
      type="number"
      name="expected_max_employee"
      :validation="['numeric', 'min:1']"
      default-value="{{ optional( $data ?? '' )->expected_max_employee }}"
      size="4">
    </v-input>
  </div>
  <div class="row">
    <v-text-area
      placeholder="{{ __('app.companies.placeholder.description') }}"
      label="{{ __('app.companies.column.description') }}"
      old="{{ old('business_description') }}"
      @error('business_description')
      error-message="{{ $message }}"
      :error="true"
      @enderror
      name="business_description"
      :validation="['required']"
      default-value="{{ optional( $data ?? '' )->business_description }}"
      size="6"
      >
    </v-text-area>
    <v-text-area
      placeholder="{{ __('app.companies.placeholder.address') }}"
      label="{{ __('app.companies.column.address') }}"
      old="{{ old('address') }}"
      @error('address')
      error-message="{{ $message }}"
      :error="true"
    @enderror
      name="address"
      :validation="['required']"
      default-value="{{ optional( $data ?? '' )->address }}"
      size="6"
      >
    </v-text-area>
  </div>
    <div class="row">
      <v-select
         placeholder="{{ __('app.companies.placeholder.type') }}"
         options="{{ collect(config('array_options.business_type'))->map(fn($r) => ['id' => $r, 'text' => $r]) }}"
         default-value="{{optional($data)->business_type}}"
         label="{{ __('app.companies.column.business_type') }}"
         name="type"
         size="6"
         old="{{ old('type') }}"
         @error('type')
         :error="true"
         error-message="{{ $message }}"
         @enderror
         ></v-select>
      <v-select
         placeholder="{{ __('app.companies.placeholder.default_currency') }}"
         options="{{ collect(config('array_options.default_currency')) }}"
         default-value="{{optional($data ?? '')->default_currency}}"
         label="{{ __('app.companies.column.default_currency') }}"
         name="default_currency"
         size="6"
         old="{{ old('default_currency') }}"
         @error('default_currency')
         :error="true"
         error-message="{{ $message }}"
         @enderror
         ></v-select>
    </div>
</x-form>

