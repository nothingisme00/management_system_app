<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

// Custom Login Page (Override Fortify)
Route::get('/login', \App\Livewire\Auth\LoginForm::class)
    ->middleware(['guest', 'prevent.back'])
    ->name('login');

Route::get('dashboard', function () {
    $user = auth()->user();

    // Redirect based on role
    if ($user->isAdmin()) {
        return redirect()->route('dashboard.admin');
    } elseif ($user->isHRD()) {
        return redirect()->route('dashboard.hrd');
    } elseif ($user->isManager()) {
        return redirect()->route('dashboard.manager');
    } elseif ($user->isKaryawan()) {
        return redirect()->route('dashboard.karyawan');
    }

    // Fallback if no role
    abort(403, 'No dashboard assigned to your role.');
})->middleware(['auth', 'verified', 'prevent.back'])->name('dashboard');

// Role-based dashboards
Route::middleware(['auth', 'verified', 'role:Admin', 'prevent.back'])->group(function () {
    Route::get('dashboard-admin', \App\Livewire\Admin\Dashboard::class)->name('dashboard.admin');
});

Route::middleware(['auth', 'verified', 'role:HRD', 'prevent.back'])->group(function () {
    Route::get('dashboard-hrd', \App\Livewire\HRD\Dashboard::class)->name('dashboard.hrd');
});

Route::middleware(['auth', 'verified', 'role:Manager', 'prevent.back'])->group(function () {
    Route::get('dashboard-manager', \App\Livewire\Manager\Dashboard::class)->name('dashboard.manager');
});

Route::middleware(['auth', 'verified', 'role:Karyawan', 'prevent.back'])->group(function () {
    Route::get('dashboard-karyawan', \App\Livewire\Karyawan\Dashboard::class)->name('dashboard.karyawan');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
