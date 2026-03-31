<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentAssignmentController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('web')->user();
        
        // Only show assignments for courses the student is enrolled in
        $enrolledCourseIds = $user->courses()->pluck('courses.id');
        
        $assignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest()
            ->paginate(15);

        return view('tenant.student.assignments', compact('assignments', 'user'));
    }

    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'file'    => 'required|file|max:20480',
            'comment' => 'nullable|string|max:1000',
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $user->id],
            [
                'tenant_id' => app('currentTenant')->id,
                'file_path' => $filePath,
                'comment'   => $request->comment,
                'status'    => 'submitted',
            ]
        );

        return back()->with('success', 'Assignment submitted successfully!');
    }
}
