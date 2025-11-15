<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Services\AuthenticationServiceInterface;
use App\Contracts\Services\AuthorizationServiceInterface;
use App\Contracts\Services\DepartmentServiceInterface;
use App\Contracts\Services\EmployeeServiceInterface;
use App\Contracts\Services\PositionServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Services\AuthenticationService;
use App\Services\AuthorizationService;
use App\Services\DepartmentService;
use App\Services\EmployeeService;
use App\Services\PositionService;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        AuthenticationServiceInterface::class => AuthenticationService::class,
        AuthorizationServiceInterface::class => AuthorizationService::class,
        DepartmentServiceInterface::class => DepartmentService::class,
        EmployeeServiceInterface::class => EmployeeService::class,
        PositionServiceInterface::class => PositionService::class,
        UserServiceInterface::class => UserService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Service bindings are automatically registered via $bindings property
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model policies
        Gate::policy(User::class, UserPolicy::class);
    }
}
