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
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'logo_dark'     => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'dashboard_bg_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'home_bg_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'remove_dashboard_bg' => 'nullable|boolean',
            'custom_domain' => 'nullable|string|max:255|unique:tenants,custom_domain,' . $tenant->id,
        ]);

        foreach (['avatar', 'logo', 'logo_dark', 'dashboard_bg_image', 'home_bg_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($tenant->$field) {
                    Storage::disk('public')->delete($tenant->$field);
                }
                $validated[$field] = $request->file($field)->store('tenant_images', 'public');
            }
        }

        if ($request->boolean('remove_dashboard_bg')) {
            if ($tenant->dashboard_bg_image) {
                Storage::disk('public')->delete($tenant->dashboard_bg_image);
            }
            $validated['dashboard_bg_image'] = null;
        }

        $tenant->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
