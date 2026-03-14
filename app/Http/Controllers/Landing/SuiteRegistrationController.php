<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SuiteRegistrationController extends Controller
{
    public function create(): View
    {
        return view('landing.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'owner_name' => 'required|string|max:255',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:tenants,email',
            'subdomain'  => [
                'required', 'string', 'min:3', 'max:30',
                'regex:/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/',
                'not_in:admin,www,api,mail,ftp',
                'unique:tenants,subdomain',
            ],
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $tenant = Tenant::create([
            'name'       => $validated['name'],
            'subdomain'  => $validated['subdomain'],
            'owner_name' => $validated['owner_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'is_active'  => true,
            'approved_at' => now(),
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'name'      => $validated['owner_name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'admin',
            'status'    => 'approved',
        ]);

        return redirect()->route('landing.success')
            ->with('portal_url', 'http://' . $tenant->subdomain . '.' . config('app.base_domain'))
            ->with('subdomain', $tenant->subdomain);
    }

    /** AJAX: Check subdomain availability */
    public function checkSubdomain(Request $request): JsonResponse
    {
        $subdomain = strtolower($request->input('subdomain', ''));
        $reserved  = ['admin', 'www', 'api', 'mail', 'ftp', 'static'];

        if (in_array($subdomain, $reserved)) {
            return response()->json(['available' => false, 'message' => 'This subdomain is reserved.']);
        }

        $exists = Tenant::where('subdomain', $subdomain)->exists();
        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'This subdomain is already taken.' : 'Great! This subdomain is available.',
        ]);
    }
}
