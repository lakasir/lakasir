<form action="../../index.html" method="post">
  <v-input icon="fa-user" placeholder="{{ __('app.install.placeholder.username') }}"
                          :prepend="true" name="username" :validation="['required', 'min:3', 'alpha_dash']"
    ></v-input>
  <v-input icon="fa-envelope" placeholder="{{ __('app.install.placeholder.email') }}"
                          :prepend="true" name="email" :validation="['required', 'email']"
    ></v-input>
  <div class="row">
    <div class="col-8">
      <div class="icheck-primary">
        <input type="checkbox" id="agreeTerms" name="terms" value="agree">
        <label for="agreeTerms">
         I agree to the <a href="#">terms</a>
        </label>
      </div>
    </div>
    <!-- /.col -->
    <div class="col-4">
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </div>
    <!-- /.col -->
  </div>
</form>

