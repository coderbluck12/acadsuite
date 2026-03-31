<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(): View
    {
        $tenant = app('currentTenant');
        $products = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('tenant.public.marketplace.index', compact('tenant', 'products'));
    }

    public function show(Product $product): View
    {
        $tenant = app('currentTenant');
        
        // Ensure product belongs to current tenant
        if ($product->tenant_id != $tenant->id || !$product->is_active) {
            abort(404);
        }

        return view('tenant.public.marketplace.show', compact('tenant', 'product'));
    }
}
