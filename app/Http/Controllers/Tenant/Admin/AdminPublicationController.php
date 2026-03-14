<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminPublicationController extends Controller
{
    public function index(): View
    {
        $publications = Publication::latest()->paginate(15);
        return view('tenant.admin.publications', compact('publications'));
    }

    public function create(): View
    {
        return view('tenant.admin.publications-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');
        if (($tenant->plan === 'free' || !$tenant->plan) && $tenant->publications()->count() >= 5) {
            return redirect()->back()->with('error', 'Free plan limit reached (max 5 publications). Please upgrade to Pro.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'authors'      => 'required|string|max:500',
            'journal'      => 'nullable|string|max:255',
            'year'         => 'nullable|digits:4',
            'abstract'     => 'nullable|string',
            'url'          => 'nullable|url',
            'cover_image'  => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('publications', 'public');
        }

        Publication::create($validated);
        return redirect()->route('tenant.admin.publications.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Publication added.');
    }

    public function edit(Publication $publication): View
    {
        return view('tenant.admin.publications-form', compact('publication'));
    }

    public function update(Request $request, Publication $publication): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'authors'      => 'required|string|max:500',
            'journal'      => 'nullable|string|max:255',
            'year'         => 'nullable|digits:4',
            'abstract'     => 'nullable|string',
            'url'          => 'nullable|url',
            'cover_image'  => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($publication->cover_image) Storage::disk('public')->delete($publication->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('publications', 'public');
        }

        $publication->update($validated);
        return redirect()->route('tenant.admin.publications.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Publication updated.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        if ($publication->cover_image) Storage::disk('public')->delete($publication->cover_image);
        $publication->delete();
        return back()->with('success', 'Publication deleted.');
    }
}
