<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::latest()->paginate(15);
        return view('tenant.admin.courses', compact('courses'));
    }

    public function create(): View { return view('tenant.admin.courses-form'); }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');
        if (($tenant->plan === 'free' || !$tenant->plan) && $tenant->courses()->count() >= 5) {
            return redirect()->back()->with('error', 'Free plan limit reached (max 5 courses). Please upgrade to Pro.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'duration'     => 'nullable|string|max:50',
            'level'        => 'nullable|string|max:50',
            'cover_image'  => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'visibility'   => 'required|in:general,private,hidden',
            'access_code'  => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('courses', 'public');
        }
        Course::create($validated);
        return redirect()->route('tenant.admin.courses.index', ['tenant' => $tenant->subdomain])->with('success', 'Course added.');
    }

    public function edit(Course $course): View { return view('tenant.admin.courses-form', compact('course')); }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'duration'     => 'nullable|string|max:50',
            'level'        => 'nullable|string|max:50',
            'cover_image'  => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'visibility'   => 'required|in:general,private,hidden',
            'access_code'  => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('cover_image')) {
            if ($course->cover_image) Storage::disk('public')->delete($course->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('courses', 'public');
        }
        $course->update($validated);
        return redirect()->route('tenant.admin.courses.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->cover_image) Storage::disk('public')->delete($course->cover_image);
        $course->delete();
        return back()->with('success', 'Course deleted.');
    }
}
