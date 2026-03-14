<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('is_published', true)->latest()->paginate(12);
        return view('tenant.public.courses', compact('courses'));
    }

    public function show(Course $course): View
    {
        return view('tenant.public.course-show', compact('course'));
    }
}
