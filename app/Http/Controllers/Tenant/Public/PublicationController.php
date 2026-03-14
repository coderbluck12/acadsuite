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
}
