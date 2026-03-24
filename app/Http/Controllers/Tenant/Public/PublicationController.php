<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        $publications = Publication::where('is_published', true)->latest()->paginate(12);
        return view('tenant.public.publications', compact('publications'));
    }

    public function show(Publication $publication)
    {
        // Only allow viewing if published
        if (!$publication->is_published) {
            abort(404);
        }

        // If not logged in, they can view details but maybe not download
        // But maybe they want restricted access altogether before logging in?
        // Prompt says "restricted before logging in, logging in should open it up"
        if (!auth()->check()) {
            return redirect()->route('tenant.login')->with('error', 'Please login to view publication details.');
        }

        return view('tenant.public.publication-details', compact('publication'));
    }
}
