<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminResourceController extends Controller
{
    public function index(): View
    {
        $resources = Resource::latest()->paginate(15);
        return view('tenant.admin.resources', compact('resources'));
    }

    public function create(): View { return view('tenant.admin.resources-form'); }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');
        if (($tenant->plan === 'free' || !$tenant->plan) && $tenant->resources()->count() >= 5) {
            return redirect()->back()->with('error', 'Free plan limit reached (max 5 resources). Please upgrade to Pro.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'file'         => 'nullable|file|max:10240',
            'file_type'    => 'nullable|string|max:50',
            'external_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('resources', 'public');
            $validated['file_type'] = $request->file('file')->getClientOriginalExtension();
        }
        unset($validated['file']);
        Resource::create($validated);
        return redirect()->route('tenant.admin.resources.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Resource added.');
    }

    public function edit(Resource $resource): View { return view('tenant.admin.resources-form', compact('resource')); }

    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'file'         => 'nullable|file|max:10240',
            'external_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);
        if ($request->hasFile('file')) {
            if ($resource->file_path) Storage::disk('public')->delete($resource->file_path);
            $validated['file_path'] = $request->file('file')->store('resources', 'public');
            $validated['file_type'] = $request->file('file')->getClientOriginalExtension();
        }
        unset($validated['file']);
        $resource->update($validated);
        return redirect()->route('tenant.admin.resources.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Resource updated.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        if ($resource->file_path) Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        return back()->with('success', 'Resource deleted.');
    }
}
