<?php

namespace App\Http\Middleware;

use App\Models\Central\Customer;
use App\Services\Tenant\TenantConnectionManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(
        protected TenantConnectionManager $connections
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $customerId = session('tenant_customer_id');

        if (! $customerId) {
            $this->clearTenantSession($request);

            return redirect()->route('tenant.login')->withErrors(['email' => 'Tenant session expired. Please login again.']);
        }

        $customer = Customer::with('database')->find($customerId);

        if (! $customer || ! $customer->database || $customer->database->status !== 'ready') {
            $this->clearTenantSession($request);

            return redirect()->route('tenant.login')->withErrors(['email' => 'Clinic account not found.']);
        }

        $this->connections->connect($customer);
        $request->attributes->set('tenant', $customer);
        view()->share('currentTenant', $customer);

        return $next($request);
    }

    /**
     * Clear tenant auth without querying the tenant DB (which may not be connected yet).
     */
    protected function clearTenantSession(Request $request): void
    {
        $guard = Auth::guard('tenant');
        $guard->forgetUser();
        $request->session()->forget([
            $guard->getName(),
            $guard->getRecallerName(),
            'tenant_customer_id',
            'tenant_code',
        ]);
    }
}
