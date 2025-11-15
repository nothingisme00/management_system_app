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

        $this->redirectByRole();
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

    protected function redirectByRole(): void
    {
        $user = Auth::user();

        // Use full page redirect (not wire:navigate) to ensure clean browser state
        // This prevents bfcache issues with back button after login
        if ($user->isAdmin()) {
            $this->redirect(route('dashboard.admin'), navigate: false);
        } elseif ($user->isHRD()) {
            $this->redirect(route('dashboard.hrd'), navigate: false);
        } elseif ($user->isManager()) {
            $this->redirect(route('dashboard.manager'), navigate: false);
        } elseif ($user->isKaryawan()) {
            $this->redirect(route('dashboard.karyawan'), navigate: false);
        } else {
            $this->redirect(route('dashboard'), navigate: false);
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form')
            ->layout('layouts.auth', ['title' => 'Login']);
    }
}
