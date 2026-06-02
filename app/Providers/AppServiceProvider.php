<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repository Interfaces
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\OrganizationRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\AuditLogRepositoryInterface;

// Repository Implementations
use App\Repositories\AuthRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\TicketRepository;
use App\Repositories\AuditLogRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(AuditLogRepositoryInterface::class, AuditLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
