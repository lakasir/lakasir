<x-form :route="$route" :title="__('app.suppliers.create.title')" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.suppliers.placeholder.code') }}"
           label="{{ __('app.suppliers.column.code') }}"
           old="{{ old('code') }}"
           @error('code')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="code"
           default-value="{{ optional( $data ?? '' )->code }}"
           info=" {{ __('app.suppliers.info.code') }}"
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
           placeholder="{{ __('app.suppliers.placeholder.email') }}"
           label="{{ __('app.suppliers.column.email') }}"
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
