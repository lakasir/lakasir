<x-form :route="$route" :title="$title" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.customers.placeholder.code') }}"
           label="{{ __('app.customers.column.code') }}"
           old="{{ old('code') }}"
           @error('code')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="code"
           default-value="{{ optional( $data ?? '' )->code }}"
           info=" {{ __('app.customers.info.code') }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.customers.placeholder.name') }}"
           label="{{ __('app.customers.column.name') }}"
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
           placeholder="{{ __('app.customers.placeholder.email') }}"
           label="{{ __('app.customers.column.email') }}"
           old="{{ old('email') }}"
           @error('email')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="email"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->email }}"
           ></v-input>
</x-form>
