<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('web')->user();
        
        // Only show courses the student is explicitly enrolled in
        $enrolledCourses = $user->courses()->latest()->take(6)->get();
        $enrolledCourseIds = $user->courses()->pluck('courses.id');

        // Only show assignments for those enrolled courses
        $assignments = Assignment::whereIn('course_id', $enrolledCourseIds)
                                 ->latest()
                                 ->take(5)
                                 ->get();

        $notifications = Notification::where('user_id', $user->id)
                                     ->whereNull('read_at')
                                     ->count();

        return view('tenant.student.dashboard', [
            'user' => $user,
            'courses' => $enrolledCourses,
            'assignments' => $assignments,
            'notifications' => $notifications
        ]);
    }
}
