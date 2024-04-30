<?php

namespace App\Livewire;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ResetPassword extends Component
{
    public $token;

    public $email;

    public $password;

    public $password_confirmation;

    protected $queryString = ['email'];

    public function mount(string $token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        session()->flash('message', 'Password reset successfully.');

        $this->reset(['password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.reset-password');
    }
}
