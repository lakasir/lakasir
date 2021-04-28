<x-form :route="$route" :title="$title" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.suppliers.placeholder.code') }}"
           label="{{ __('app.suppliers.column.code') }}"
           old="{{ old('code') }}"
           @error('code')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="code"
           :validation="['']"
           default-value="{{ optional( $data ?? '' )->code }}"
           info=" {{ __('app.customers.info.code') }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.suppliers.placeholder.name') }}"
           label="{{ __('app.suppliers.column.name') }}"
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
           placeholder="{{ __('app.suppliers.placeholder.shop_name') }}"
           label="{{ __('app.suppliers.column.shop_name') }}"
           old="{{ old('shop_name') }}"
           @error('shop_name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="shop_name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->shop_name }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.suppliers.placeholder.phone') }}"
           label="{{ __('app.suppliers.column.phone') }}"
           old="{{ old('phone') }}"
           @error('phone')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="phone"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->phone }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.suppliers.placeholder.address') }}"
           label="{{ __('app.suppliers.column.address') }}"
           old="{{ old('address') }}"
           @error('address')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="address"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->address }}"
           ></v-input>
</x-form>
