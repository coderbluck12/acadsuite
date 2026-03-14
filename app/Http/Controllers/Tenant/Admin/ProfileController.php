<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $tenant = app('currentTenant');
        return view('tenant.admin.profile', compact('tenant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'owner_name'    => 'required|string|max:255',
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'bio'           => 'nullable|string|max:5000',
            'orcid_url'     => 'nullable|url',
            'address'       => 'nullable|string|max:500',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'custom_domain' => 'nullable|string|max:255|unique:tenants,custom_domain,' . $tenant->id,
        ]);

        if ($request->hasFile('avatar')) {
            if ($tenant->avatar) {
                Storage::disk('public')->delete($tenant->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $tenant->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
