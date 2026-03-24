<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TenantAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('tenant.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $tenant = app('currentTenant');
        
        $user   = User::where('email', $credentials['email'])
                      ->where('tenant_id', $tenant->id)
                      ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials for this portal.'])->withInput();
        }

        if ($user->status !== 'approved') {
            return back()->withErrors(['email' => 'Your account is pending approval.'])->withInput();
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // FIX: Redirect to the URL path instead of the route name!
        // This keeps the user securely on coderbluck.acadsuite.tertab.com
        return $user->role === 'admin'
            ? redirect('/admin/profile')
            : redirect('/student/dashboard');
    }

    public function showRegister(): View
    {
        return view('tenant.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $exists = User::where('email', $validated['email'])
                      ->where('tenant_id', $tenant->id)
                      ->exists();
                      
        if ($exists) {
            return back()->withErrors(['email' => 'Email already registered on this portal.'])->withInput();
        }

        User::create([
            'tenant_id' => $tenant->id,
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'student',
            'status'    => 'pending',
        ]);

        // FIX: Redirect to the /login path instead of the route name
        return redirect('/login')->with('success', 'Registration successful! Please wait for approval.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // FIX: Redirect to the /login path instead of the route name
        return redirect('/login');
    }
}