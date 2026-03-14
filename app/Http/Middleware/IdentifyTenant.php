<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host       = $request->getHost();
        $baseDomain = config('app.base_domain', 'acadsuite.local');
        $isLocalhost = in_array($host, ['localhost', '127.0.0.1', '::1']);

        // ── DEV MODE: localhost + ?tenant=subdomain ──────────────────────────
        if ($isLocalhost) {
            $subdomain = $request->query('tenant');
            if (!$subdomain) {
                // No tenant param on localhost → let the request through (landing/superadmin routes handle it)
                return $next($request);
            }

            $tenant = Tenant::where('subdomain', $subdomain)->first();
            if (!$tenant) {
                abort(404, "Tenant '{$subdomain}' not found. Register it on the landing page first.");
            }
            if (!$tenant->is_active) {
                abort(403, 'This portal has been deactivated.');
            }

            app()->instance('currentTenant', $tenant);
            view()->share('tenant', $tenant);
            return $next($request);
        }

        // ── PRODUCTION MODE: Custom Domain OR Subdomain routing ────────────────
        // 1. Check if the current host exactly matches a mapped custom domain
        $tenant = Tenant::where('custom_domain', $host)->first();

        // 2. If no custom domain matches, fallback to subdomain parsing
        if (!$tenant) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);

            // Root domain or reserved subdomains → skip (let other route groups handle)
            if ($subdomain === $baseDomain || $subdomain === 'admin' || $subdomain === 'www') {
                return $next($request);
            }

            $tenant = Tenant::where('subdomain', $subdomain)->first();
        }

        if (!$tenant) {
            abort(404, 'Portal not found.');
        }
        if (!$tenant->is_active) {
            abort(403, 'This portal has been deactivated.');
        }

        app()->instance('currentTenant', $tenant);
        view()->share('tenant', $tenant);

        return $next($request);
    }
}
