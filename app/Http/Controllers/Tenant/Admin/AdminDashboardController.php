<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Publication;
use App\Models\Resource;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $tenant = app('currentTenant');

        $totalStudents    = User::where('tenant_id', $tenant->id)->where('role', 'student')->count();
        $pendingStudents  = User::where('tenant_id', $tenant->id)->where('role', 'student')->where('status', 'pending')->count();
        $totalCourses     = Course::count();
        $totalPublications= Publication::count();
        $totalAssignments = Assignment::count();
        $totalResources   = Resource::count();
        $walletBalance    = $tenant->wallet_balance ?? 0;

        $recentStudents = User::where('tenant_id', $tenant->id)
            ->where('role', 'student')
            ->latest()
            ->take(5)
            ->get();

        $recentAssignments = Assignment::with('course')
            ->latest()
            ->take(5)
            ->get();

        return view('tenant.admin.dashboard', compact(
            'tenant',
            'totalStudents',
            'pendingStudents',
            'totalCourses',
            'totalPublications',
            'totalAssignments',
            'totalResources',
            'walletBalance',
            'recentStudents',
            'recentAssignments'
        ));
    }
}
