<x-form :route="$route" :title="$title" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.customer_types.placeholder.name') }}"
           label="{{ __('app.customer_types.column.name') }}"
           old="{{ old('name') }}"
           @error('name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->name }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.customer_types.placeholder.default_point') }}"
           label="{{ __('app.customer_types.column.default_point') }}"
           old="{{ old('default_point') }}"
           @error('default_point')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           type="number"
           name="default_point"
           default-value="{{ optional( $data ?? '' )->default_point }}"
           ></v-input>
</x-form>
