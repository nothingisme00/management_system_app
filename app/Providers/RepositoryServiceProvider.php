<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\DepartmentRepositoryInterface;
use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Contracts\Repositories\PositionRepositoryInterface;
use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\PositionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Repository Service Provider
 *
 * Binds repository interfaces to their concrete implementations.
 * This enables dependency injection throughout the application.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        DepartmentRepositoryInterface::class => DepartmentRepository::class,
        PositionRepositoryInterface::class => PositionRepository::class,
        EmployeeRepositoryInterface::class => EmployeeRepository::class,
    ];

    /**
     * Register repository bindings.
     */
    public function register(): void
    {
        // Bindings are automatically registered via $bindings property
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
