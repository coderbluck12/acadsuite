<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $totalAcademics = Tenant::count();
        return view('landing.home', compact('totalAcademics'));
    }

    public function success(): View
    {
        return view('landing.success');
    }
}
