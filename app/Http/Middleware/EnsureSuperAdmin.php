<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('superadmin')->check()) {
            return redirect()->route('superadmin.login');
        }

        if (! auth('superadmin')->user()->is_active) {
            auth('superadmin')->logout();

            return redirect()->route('superadmin.login')->withErrors(['email' => 'Account deactivated.']);
        }

        return $next($request);
    }
}
