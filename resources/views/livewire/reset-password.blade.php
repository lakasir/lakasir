<div>
  @if (session()->has('message'))
    <div class="mb-4 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
      {{ session('message') }}
    </div>
  @endif

  <h1 class="text-lg font-semibold">Reset Password</h1>

  <form wire:submit.prevent="resetPassword" method="POST" class="mt-6 space-y-4">
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
      <label for="email_address" class="block text-sm font-medium">E-Mail Address</label>
      <input type="text" id="email_address" class="mt-1 block w-full border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] text-sm shadow-sm focus:border-[color:var(--ui-primary)] focus:ring-[color:var(--ui-primary)]" wire:model.lazy="email" required readonly value="{{ $email }}">
      @if ($errors->has('email'))
        <span class="mt-1 block text-sm text-red-600">{{ $errors->first('email') }}</span>
      @endif
    </div>

    <div>
      <label for="password" class="block text-sm font-medium">Password</label>
      <input type="password" id="password" class="mt-1 block w-full border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] text-sm shadow-sm focus:border-[color:var(--ui-primary)] focus:ring-[color:var(--ui-primary)]" wire:model.lazy="password" required autofocus>
      @if ($errors->has('password'))
        <span class="mt-1 block text-sm text-red-600">{{ $errors->first('password') }}</span>
      @endif
    </div>

    <div>
      <label for="password-confirm" class="block text-sm font-medium">Confirm Password</label>
      <input type="password" id="password-confirm" class="mt-1 block w-full border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] text-sm shadow-sm focus:border-[color:var(--ui-primary)] focus:ring-[color:var(--ui-primary)]" wire:model.lazy="password_confirmation" required autofocus>
      @if ($errors->has('password_confirmation'))
        <span class="mt-1 block text-sm text-red-600">{{ $errors->first('password_confirmation') }}</span>
      @endif
    </div>

    <button type="submit" class="w-full bg-[color:var(--ui-primary)] px-4 py-2 text-sm font-semibold text-white">
      Reset Password
    </button>
  </form>
</div>
