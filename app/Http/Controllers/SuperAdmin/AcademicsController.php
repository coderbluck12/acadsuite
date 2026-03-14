<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicsController extends Controller
{
    public function index(): View
    {
        $academics = Tenant::latest()->paginate(15);
        return view('superadmin.academics.index', compact('academics'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['users']);
        $studentCount = $tenant->users()->where('role', 'student')->count();
        return view('superadmin.academics.show', compact('tenant', 'studentCount'));
    }

    public function toggle(Tenant $tenant): RedirectResponse
    {
        $tenant->update(['is_active' => !$tenant->is_active]);
        $status = $tenant->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Portal has been {$status}.");
    }

    public function updatePlan(Tenant $tenant, string $plan): RedirectResponse
    {
        if (!in_array($plan, ['free', 'pro'])) {
            return back()->with('error', 'Invalid plan selected.');
        }

        $tenant->update(['plan' => $plan]);
        return back()->with('success', "Tenant plan updated to " . ucfirst($plan) . ".");
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();
        return redirect()->route('superadmin.academics.index')
                         ->with('success', 'Academic portal deleted.');
    }
}
