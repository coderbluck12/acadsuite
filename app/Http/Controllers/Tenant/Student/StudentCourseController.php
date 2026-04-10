<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentCourseController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('web')->user();
        
        // Fetch published courses that are either general OR the user is already enrolled in.
        $courses = Course::where('is_published', true)
                         ->where(function($q) use ($user) {
                             $q->where('visibility', 'general')
                               ->orWhereHas('students', function($sq) use ($user) {
                                   $sq->where('user_id', $user->id);
                               });
                         })
                         ->with(['students' => function ($query) use ($user) {
                             $query->where('user_id', $user->id);
                         }])
                         ->latest()
                         ->paginate(12);

        return view('tenant.student.courses', compact('courses', 'user'));
    }

    public function joinPrivate(Request $request): RedirectResponse
    {
        $request->validate(['access_code' => 'required|string']);
        $user = Auth::guard('web')->user();
        
        $course = Course::where('access_code', $request->access_code)
                        ->where('visibility', 'private')
                        ->where('is_published', true)
                        ->first();
                        
        if (!$course) {
            return redirect()->back()->with('error', 'Invalid access code or course not available.');
        }

        return redirect()->route('tenant.student.courses.show', [
            'tenant' => app('currentTenant')->subdomain,
            'course' => $course->id
        ])->with('success', 'Course found! Please review the details and click Enroll.');
    }

    public function show(Course $course)
    {
        $user = Auth::guard('web')->user();
        $tenant = app('currentTenant');
        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();
        $resources = $course->resources()->where('is_published', true)->get();

        return view('tenant.student.course-show', compact('course', 'user', 'isEnrolled', 'tenant', 'resources'));
    }

    public function enroll(Course $course): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        // Check if already enrolled
        if (!$course->students()->where('user_id', $user->id)->exists()) {
            $course->students()->attach($user->id, ['enrolled_at' => now()]);
            return redirect()->back()->with('success', 'You have successfully enrolled in the course.');
        }

        return redirect()->back()->with('info', 'You are already enrolled in this course.');
    }
}
