<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\View\View;

class AdminPurchaseController extends Controller
{
    public function index(): View
    {
        $tenant = app('currentTenant');
        
        // Eager load relationships for the purchase table display
        $purchases = Purchase::with(['user', 'product'])
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->paginate(20);

        return view('tenant.admin.purchases.index', compact('tenant', 'purchases'));
    }
}
