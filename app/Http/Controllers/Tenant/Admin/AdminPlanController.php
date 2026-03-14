<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPlanController extends Controller
{
    public function index(): View
    {
        $tenant = app('currentTenant');
        
        $stats = [
            'blogs' => $tenant->blogs()->count(),
            'courses' => $tenant->courses()->count(),
            'publications' => $tenant->publications()->count(),
            'resources' => $tenant->resources()->count(),
            'assignments' => $tenant->assignments()->count(),
        ];

        return view('tenant.admin.plans', compact('tenant', 'stats'));
    }
}
