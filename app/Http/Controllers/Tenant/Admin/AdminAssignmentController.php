<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminAssignmentController extends Controller
{
    public function index(): View
    {
        $assignments  = Assignment::with(['course', 'submissions'])->latest()->paginate(15);
        $courses      = Course::where('is_published', true)->get();
        return view('tenant.admin.assignments', compact('assignments', 'courses'));
    }

    public function create(): View
    {
        $courses = Course::where('is_published', true)->get();
        return view('tenant.admin.assignments-form', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');
        if (($tenant->plan === 'free' || !$tenant->plan) && $tenant->assignments()->count() >= 5) {
            return redirect()->back()->with('error', 'Free plan limit reached (max 5 assignments). Please upgrade to Pro.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id'   => 'nullable|exists:courses,id',
            'due_date'    => 'nullable|date',
            'file'        => 'nullable|file|max:10240',
        ]);
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }
        unset($validated['file']);
        Assignment::create($validated);
        return redirect()->route('tenant.admin.assignments.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Assignment created.');
    }

    public function edit(Assignment $assignment): View
    {
        $courses = Course::where('is_published', true)->get();
        return view('tenant.admin.assignments-form', compact('assignment', 'courses'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id'   => 'nullable|exists:courses,id',
            'due_date'    => 'nullable|date',
            'file'        => 'nullable|file|max:10240',
        ]);
        if ($request->hasFile('file')) {
            if ($assignment->file_path) Storage::disk('public')->delete($assignment->file_path);
            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }
        unset($validated['file']);
        $assignment->update($validated);
        return redirect()->route('tenant.admin.assignments.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Assignment updated.');
    }

    public function show(Assignment $assignment): View
    {
        $assignment->load('submissions.student');
        return view('tenant.admin.assignments-show', compact('assignment'));
    }

    public function grade(Request $request, Assignment $assignment, \App\Models\AssignmentSubmission $submission): RedirectResponse
    {
        $submission->update(['status' => 'graded']);
        return back()->with('success', 'Submission marked as graded.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        if ($assignment->file_path) Storage::disk('public')->delete($assignment->file_path);
        $assignment->delete();
        return back()->with('success', 'Assignment deleted.');
    }
}
