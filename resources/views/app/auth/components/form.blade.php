<form action="{{ route('login') }}" method="post">
  @csrf
  <v-input icon="fa-envelope"
           placeholder="{{ __('app.auth.placeholder.identity') }}"
           :prepend="true"
           old="{{ old('identity') }}"
           @error('identity')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="identity"
           :validation="['required']"
    ></v-input>
  <v-input icon="fa-lock"
           placeholder="{{ __('app.auth.placeholder.password') }}"
           type="password"
           old="{{ old('password') }}"
           @error('password')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           :prepend="true"
           name="password"
           :validation="['required']"
    ></v-input>
  <v-checkbox icon="fa-lock"
           placeholder="{{ __('app.auth.placeholder.remember') }}"
           old="{{ old('remember') }}"
           name="remember"
           label="{{ __('app.auth.label.remember') }}"
           default-value="true"
    ></v-checkbox>
  <div class="row">
    <div class="col-12">
      <v-button float="right" type="submit" text="{{ __('app.global.submit') }}" icon="fas fa-check" ></v-button>
      <v-button to="{{ route('cashier') }}" color="info" class="mr-2" text="{{ __('app.global.login_cashier') }}" icon="fas fa-check" ></v-button>
    </div>
  </div>
</form>
