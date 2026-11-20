<?php

namespace App\Http\Middleware;

use App\Models\Central\Customer;
use App\Services\Tenant\TenantConnectionManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Soft-connect the tenant DB when a clinic session exists.
 * Needed so guest:tenant / auth checks do not hit an empty tenant connection.
 */
class PrepareTenantConnection
{
    public function __construct(
        protected TenantConnectionManager $connections
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $customerId = session('tenant_customer_id');

        if (! $customerId) {
            return $next($request);
        }

        $customer = Customer::with('database')->find($customerId);

        if ($customer && $customer->database && $customer->database->status === 'ready') {
            $this->connections->connect($customer);
            $request->attributes->set('tenant', $customer);
            view()->share('currentTenant', $customer);
        }

        return $next($request);
    }
}
