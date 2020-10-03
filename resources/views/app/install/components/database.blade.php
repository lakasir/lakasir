@if (session('database_error_message'))
    <div class="alert alert-danger">
        {{ session('database_error_message') }}
    </div>
@endif

<form action="{{ route('install.databaseStore') }}" method="post">
  @csrf
  <v-input icon="fa-database"
           placeholder="{{ __('app.install.placeholder.database.host') }}"
           default-value="127.0.0.1"
           old="{{ old('host') }}"
           default-value="host"
           @error('host') :error="true"
           error-message="{{ $message }}"
           @enderror
           :prepend="true" name="host" :validation="['ip']"
  ></v-input>
  <v-input icon="fa-database"
           placeholder="{{ __('app.install.placeholder.database.name') }}"
           :prepend="true"
           name="name" :validation="['required', 'alpha_dash']"
           old="{{ old('name') }}"
           @error('name') :error="true"
           error-message="{{ $message }}"
           @enderror
           ></v-input>

  <v-input icon="fa-database"
           placeholder="{{ __('app.install.placeholder.database.username') }}"
           :prepend="true"
           name="username"
           old="{{ old('username') }}"
           :validation="['required', 'alpha_dash']"
           @error('username') :error="true"
           error-message="{{ $message }}"
           @enderror

           ></v-input>
  <v-input icon="fa-database"
           placeholder="{{ __('app.install.placeholder.database.password') }}"
           type="password"
           old="{{ old('password') }}"
           @error('password')
           :error="true"
           error-message="{{ $message }}"
           @enderror
           :prepend="true" name="password" :validation="['required', 'alpha_dash']"
    ></v-input>
  <div class="row">
    <div class="col-8">
    </div>
    <!-- /.col -->
    <div class="col-4">
      <button type="submit" class="btn btn-primary btn-block"> {{ __('app.install.next') }} <i class="fas fa-arrow-circle-right"></i></button>
    </div>
    <!-- /.col -->
  </div>
</form>

