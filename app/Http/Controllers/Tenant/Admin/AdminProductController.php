<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(15);
        return view('tenant.admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('tenant.admin.products.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'format'      => 'required|in:hard_copy,soft_copy',
            'file_path'   => 'nullable|file|mimes:pdf,epub,doc,docx,zip|max:51200', // max 50MB
            'image_path'  => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('products/images', 'public');
        }
        
        if ($request->hasFile('file_path') && $validated['format'] === 'soft_copy') {
            $validated['file_path'] = $request->file('file_path')->store('products/files', 'public');
        }

        Product::create($validated);
        return redirect()->route('tenant.admin.products.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Product added to marketplace.');
    }

    public function edit(Product $product): View
    {
        return view('tenant.admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'format'      => 'required|in:hard_copy,soft_copy',
            'file_path'   => 'nullable|file|mimes:pdf,epub,doc,docx,zip|max:51200',
            'image_path'  => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($product->image_path) Storage::disk('public')->delete($product->image_path);
            $validated['image_path'] = $request->file('image_path')->store('products/images', 'public');
        }

        if ($request->hasFile('file_path') && $validated['format'] === 'soft_copy') {
            if ($product->file_path) Storage::disk('public')->delete($product->file_path);
            $validated['file_path'] = $request->file('file_path')->store('products/files', 'public');
        }

        $product->update($validated);
        return redirect()->route('tenant.admin.products.index', ['tenant' => app('currentTenant')->subdomain])->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) Storage::disk('public')->delete($product->image_path);
        if ($product->file_path) Storage::disk('public')->delete($product->file_path);
        
        $product->delete();
        return back()->with('success', 'Product removed from marketplace.');
    }
}
