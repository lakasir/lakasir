<x-form :route="$route" :title="$title" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.user.placeholder.username') }}"
           label="{{ __('app.user.column.username') }}"
           old="{{ old('username') }}"
           @error('username')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="username"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->username }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.user.placeholder.email') }}"
           label="{{ __('app.user.column.email') }}"
           old="{{ old('email') }}"
           @error('email')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="email"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->email }}"
           ></v-input>
  <v-input icon="fa-lock"
           placeholder="{{ __('app.user.placeholder.password') }}"
           label="{{ __('app.user.column.password') }}"
           old="{{ old('password') }}"
           type="password"
           @error('password')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="password"
           :validation="['required']"
           ></v-input>
  <v-input icon="fa-lock"
           placeholder="{{ __('app.user.placeholder.password_confirmation') }}"
           label="{{ __('app.user.column.password_confirmation') }}"
           old="{{ old('password_confirmation') }}"
           type="password"
           @error('password_confirmation')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="password_confirmation"
           :validation="['required']"
           ></v-input>
  {{-- <a href="{{ route('role.create') }}" class="float-right" target="_blank"> {{ __('app.categories.create.title') }}</a> --}}
  <select2
     :options="{{ $roles }}"
     default-value="{{ optional(optional( $data ?? '' )->getRoleNames() ?? '')->first() }}"
     label="{{ __('app.user.column.role') }}"
     name="role"
     old="{{ json_encode(old('role')) }}"
     @error('role')
     error-message="{{ $message }}"
     :error="true"
     @enderror
     >
     <option disabled value="0"> {{ __('app.user.placeholder.role') }}</option>
  </select2>
</x-form>
