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
        
        // Fetch published courses and eager load the user's enrollment
        $courses = Course::where('is_published', true)
                         ->with(['students' => function ($query) use ($user) {
                             $query->where('user_id', $user->id);
                         }])
                         ->latest()
                         ->paginate(12);

        return view('tenant.student.courses', compact('courses', 'user'));
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
