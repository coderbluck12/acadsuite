<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('is_published', true)
                         ->where('visibility', 'general')
                         ->where(function ($query) {
                             $query->where('access_type', 'public')->orWhereNull('access_type');
                         })
                         ->latest()
                         ->paginate(12);
        return view('tenant.public.courses', compact('courses'));
    }

    public function show(Course $course): View
    {
        return view('tenant.public.course-show', compact('course'));
    }
}
