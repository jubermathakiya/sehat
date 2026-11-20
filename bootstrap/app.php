<?php

use App\Http\Middleware\EnsureSubscriptionActive;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\IdentifyTenant;
use App\Http\Middleware\PrepareTenantConnection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            require __DIR__.'/../routes/superadmin.php';
            require __DIR__.'/../routes/tenant.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'superadmin' => EnsureSuperAdmin::class,
            'tenant.prepare' => PrepareTenantConnection::class,
            'tenant.identify' => IdentifyTenant::class,
            'tenant.subscription' => EnsureSubscriptionActive::class,
        ]);

        // Tenant DB must be configured before auth/guest resolve the tenant user.
        // Laravel's middleware priority otherwise moves Authenticate ahead of
        // IdentifyTenant, causing "No database selected" on the tenant connection.
        $middleware->prependToPriorityList(
            before: \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            prepend: PrepareTenantConnection::class,
        );
        $middleware->prependToPriorityList(
            before: \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            prepend: IdentifyTenant::class,
        );
        $middleware->prependToPriorityList(
            before: \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            prepend: EnsureSubscriptionActive::class,
        );
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('subscriptions:process-expiry')->dailyAt('01:00');
        $schedule->command('subscriptions:send-reminders')->dailyAt('09:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
