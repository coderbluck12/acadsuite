<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total'      => Tenant::count(),
            'active'     => Tenant::where('is_active', true)->count(),
            'inactive'   => Tenant::where('is_active', false)->count(),
            'thisMonth'  => Tenant::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count(),
        ];

        $recentAcademics = Tenant::latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentAcademics'));
    }
}
