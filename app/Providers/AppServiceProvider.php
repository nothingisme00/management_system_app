<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Services\AuthenticationServiceInterface;
use App\Contracts\Services\AuthorizationServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Services\AuthenticationService;
use App\Services\AuthorizationService;
use App\Services\UserService;
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
        //
    }
}
