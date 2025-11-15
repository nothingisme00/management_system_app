<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Contracts\Services\AuthenticationServiceInterface;
use App\Contracts\Services\AuthorizationServiceInterface;
use App\DTOs\LoginDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Component;

/**
 * Login Form Component
 *
 * Handles user authentication using clean architecture with services.
 */
class LoginForm extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Constructor with dependency injection.
     */
    public function __construct(
        protected AuthenticationServiceInterface $authService,
        protected AuthorizationServiceInterface $authorizationService
    ) {
    }

    /**
     * Handle login submission.
     */
    public function login(): void
    {
        $this->validate();

        try {
            // Create DTO from form data
            $credentials = new LoginDTO(
                email: $this->email,
                password: $this->password,
                remember: $this->remember
            );

            // Authenticate via service (handles rate limiting, auth, events)
            $userDTO = $this->authService->login($credentials);

            // Redirect to role-specific dashboard
            $this->redirectByRole();
        } catch (ValidationException $e) {
            // Re-throw validation exceptions to display errors
            throw $e;
        }
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    protected function redirectByRole(): void
    {
        $user = Auth::user();

        // Get dashboard route from authorization service
        $dashboardRoute = $this->authorizationService->getDashboardRoute($user);

        // Use full page redirect (not wire:navigate) to ensure clean browser state
        // This prevents bfcache issues with back button after login
        $this->redirect(route($dashboardRoute), navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.login-form')
            ->layout('layouts.auth', ['title' => 'Login']);
    }
}
