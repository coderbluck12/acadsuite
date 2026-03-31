<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class TenantPasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        $tenant = app('currentTenant');
        return view('tenant.auth.forgot-password', compact('tenant'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $tenant = app('currentTenant');

        // We only want to send reset link to users that belong to this tenant or are super admins
        // Actually, Laravel's password broker handles checking the users table.
        // We will just use the default broker for now.
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        $tenant = app('currentTenant');
        return view('tenant.auth.reset-password', [
            'tenant' => $tenant,
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('tenant.login', ['tenant' => app('currentTenant')->subdomain])->with('success', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
