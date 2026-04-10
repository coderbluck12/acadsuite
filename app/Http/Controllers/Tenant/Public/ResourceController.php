<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(): View
    {
        $user = auth()->guard('web')->user();
        $query = Resource::where('is_published', true);
        
        if ($user) {
            $enrolledCourseIds = $user->courses()->pluck('courses.id')->toArray();
            $query->where(function($q) use ($enrolledCourseIds) {
                $q->whereNull('course_id')
                  ->orWhere('is_general', true)
                  ->orWhereIn('course_id', $enrolledCourseIds);
            });
        } else {
            $query->where(function($q) {
                $q->whereNull('course_id')
                  ->orWhere('is_general', true);
            });
        }
        
        $resources = $query->latest()->paginate(12);
        return view('tenant.public.resources', compact('resources'));
    }

    public function request(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'nullable|string|max:20',
            'message'    => 'required|string|max:2000',
        ]);

        ResourceRequest::create($request->only(['first_name', 'last_name', 'email', 'phone', 'message']));

        return back()->with('success', 'Your resource request has been submitted!');
    }
}
