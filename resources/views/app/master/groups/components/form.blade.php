<x-form :route="$route" :title="__('app.groups.create.title')" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.groups.placeholder.name') }}"
           label="{{ __('app.groups.column.name') }}"
           old="{{ old('name') }}"
           @error('name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->name }}"
           ></v-input>
    <select2
      :options="{{ $customers }}"
      :multiple="true"
      default-value="{{ optional(optional( $data ?? '' )->customers ?? '')->pluck('id') }}"
      label="{{ __('app.groups.column.customer') }}"
      name="customer_id[]"
      old="{{ json_encode(old('customer_id')) }}"
      @error('customer_id')
      error-message="{{ $message }}"
      :error="true"
      @enderror
      >
      <option disabled value="0"> {{ __('app.groups.placeholder.customer') }}</option>
    </select2>
</x-form>
