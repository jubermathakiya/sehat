<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = $request->attributes->get('tenant');

        if (! $customer) {
            return redirect()->route('tenant.login');
        }

        if (in_array($customer->account_status, ['suspended', 'inactive', 'failed', 'pending', 'provisioning'], true)) {
            $this->clearTenantSession($request);

            return redirect()->route('tenant.login')->withErrors([
                'email' => 'Your clinic account is '.$customer->account_status.'. Please contact support.',
            ]);
        }

        if (! $customer->isAccessAllowed()) {
            $this->clearTenantSession($request);

            return redirect()->route('tenant.login')->withErrors([
                'email' => 'Your subscription has expired. Please renew to continue.',
            ]);
        }

        if ($customer->isInGracePeriod()) {
            view()->share(
                'subscriptionGraceWarning',
                'Your subscription has expired. You are in a grace period — please renew soon to avoid access suspension.'
            );
        }

        return $next($request);
    }

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
