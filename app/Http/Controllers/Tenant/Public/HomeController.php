<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Publication;
use App\Models\Resource;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $tenant       = app('currentTenant');
        $publications = Publication::where('is_published', true)->latest()->take(3)->get();
        $courses      = Course::where('is_published', true)->latest()->take(3)->get();
        $blogs        = Blog::where('is_published', true)->latest()->take(2)->get();
        $resources    = Resource::where('is_published', true)->latest()->take(6)->get();

        return view('tenant.public.home', compact('tenant', 'publications', 'courses', 'blogs', 'resources'));
    }
}
