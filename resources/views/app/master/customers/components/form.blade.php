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
  <a href="{{ route('type_customer.create') }}" class="float-right" target="_blank"> {{ __('app.customer_types.create.title') }}</a>
  <select2
     default-value="{{ optional(optional( $data ?? '' )->customerType ?? '')->id }}"
     label="{{ __('app.customer_types.title') }}"
     placeholder="{{ __('app.customer_types.placeholder.name') }}"
     name="customer_type_id"
     url="{{ route('type_customer.index') }}"
     keytext="id"
     text="name"
     old="{{ json_encode(old('customer_type_id')) }}"
     @error('customer_type_id')
     error-message="{{ $message }}"
     :error="true"
     @enderror
     >
  </select2>
</x-form>
