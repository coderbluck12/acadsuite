<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminBlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::latest()->paginate(15);
        return view('tenant.admin.blogs', compact('blogs'));
    }

    public function create(): View { return view('tenant.admin.blogs-form'); }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');
        if (($tenant->plan === 'free' || !$tenant->plan) && $tenant->blogs()->count() >= 5) {
            return redirect()->back()->with('error', 'Free plan limit reached (max 5 blogs). Please upgrade to Pro.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'content'      => 'required|string',
            'cover_image'  => 'nullable|image|max:3072',
            'is_published' => 'boolean',
        ]);
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }
        if ($validated['is_published'] ?? false) {
            $validated['published_at'] = now();
        }
        Blog::create($validated);
        return redirect()->route('tenant.admin.blogs.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Blog post created.');
    }

    public function edit(Blog $blog): View { return view('tenant.admin.blogs-form', compact('blog')); }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'content'      => 'required|string',
            'cover_image'  => 'nullable|image|max:3072',
            'is_published' => 'boolean',
        ]);
        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image) Storage::disk('public')->delete($blog->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }
        if (($validated['is_published'] ?? false) && !$blog->published_at) {
            $validated['published_at'] = now();
        }
        $blog->update($validated);
        return redirect()->route('tenant.admin.blogs.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Blog post updated.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        if ($blog->cover_image) Storage::disk('public')->delete($blog->cover_image);
        $blog->delete();
        return back()->with('success', 'Blog post deleted.');
    }
}
