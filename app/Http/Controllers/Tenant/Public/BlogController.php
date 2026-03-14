<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::where('is_published', true)->latest()->paginate(9);
        return view('tenant.public.blogs', compact('blogs'));
    }

    public function show(Blog $blog): View
    {
        $related = Blog::where('is_published', true)
                        ->where('id', '!=', $blog->id)
                        ->latest()->take(2)->get();
        return view('tenant.public.blog-show', compact('blog', 'related'));
    }
}
