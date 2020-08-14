<form action="{{ route('install.userStore') }}" method="post">
  @csrf
  <v-input icon="fa-user"
           placeholder="{{ __('app.install.placeholder.user.username') }}"
           :prepend="true"
           old="{{ old('username') }}"
           @error('username')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="username"
           :validation="['required', 'min:3', 'alpha_dash']"
    ></v-input>
  <v-input icon="fa-envelope"
           placeholder="{{ __('app.install.placeholder.user.email') }}"
           :prepend="true"
           old="{{ old('email') }}"
           @error('email')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="email"
           :validation="['required', 'email']"
    ></v-input>
  <v-input icon="fa-lock"
           placeholder="{{ __('app.install.placeholder.user.password') }}"
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
  <v-input icon="fa-lock"
           placeholder="{{ __('app.install.placeholder.user.password_confirmation') }}"
          type="password"
          old="{{ old('password_confirmation') }}"
           @error('password_confirmation')
           error-message="{{ $message }}"
           :error="true"
           @enderror
          :prepend="true"
          name="password_confirmation"
          :validation="['required']"
    ></v-input>
  <div class="row">
    <div class="col-8">
    </div>
    <!-- /.col -->
    <div class="col-4">
      <button type="submit" class="btn btn-primary btn-block"
        > {{ __('app.install.next') }} <i class="fas fa-arrow-circle-right"></i>
      </button>
    </div>
    <!-- /.col -->
  </div>
</form>
