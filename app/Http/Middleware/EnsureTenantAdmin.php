<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('tenant.login');
        }

        $user = Auth::guard('web')->user();

        if ($user->role !== 'admin') {
            abort(403, 'Admin access required.');
        }

        if ($user->status !== 'approved') {
            Auth::guard('web')->logout();
            return redirect()->route('tenant.login')->with('error', 'Your account is not approved.');
        }

        return $next($request);
    }
}
