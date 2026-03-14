<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('tenant.login');
        }

        $user = Auth::guard('web')->user();

        if ($user->role !== 'student') {
            abort(403, 'Student access required.');
        }

        if ($user->status !== 'approved') {
            Auth::guard('web')->logout();
            return redirect()->route('tenant.login')->with('error', 'Your account is pending approval.');
        }

        return $next($request);
    }
}
