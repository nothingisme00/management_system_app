<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Component;

class LoginForm extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        session()->regenerate();

        $this->redirectIntended();
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return strtolower($this->email).'|'.request()->ip();
    }

    protected function redirectIntended(): void
    {
        $user = Auth::user();

        // Redirect based on role
        if ($user->isAdmin()) {
            $this->redirect(route('dashboard.admin'), navigate: true);
        } elseif ($user->isHRD()) {
            $this->redirect(route('dashboard.hrd'), navigate: true);
        } elseif ($user->isManager()) {
            $this->redirect(route('dashboard.manager'), navigate: true);
        } elseif ($user->isKaryawan()) {
            $this->redirect(route('dashboard.karyawan'), navigate: true);
        } else {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form')
            ->layout('components.layouts.auth', ['title' => 'Login']);
    }
}
