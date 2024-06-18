<div>
  <main class="login-form">
  <div>
    @if (session()->has('message'))
    <div class="alert alert-success">
      {{ session('message') }}
    </div>
    @endif
  </div>
    <div class="cotainer">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Reset Password</div>
            <div class="card-body">
              <form wire:submit.prevent="resetPassword" method="POST">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">
                  <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                  <div class="col-md-6">
                    <input type="text" id="email_address" class="form-control" wire:model.lazy="email" required readonly value="{{ $email }}">
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                  <div class="col-md-6">
                    <input type="password" id="password" class="form-control" wire:model.lazy="password" required autofocus>
                    @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                  <div class="col-md-6">
                    <input type="password" id="password-confirm" class="form-control" wire:model.lazy="password_confirmation" required autofocus>
                    @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                  </div>
                </div>

                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-lakasir-primary">
                    Reset Password
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
