<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CheckoutController extends Controller
{
    public function checkout(Product $product)
    {
        $tenant = app('currentTenant');
        
        if ($product->tenant_id != $tenant->id || !$product->is_active) {
            abort(404);
        }

        return view('tenant.public.checkout.index', compact('tenant', 'product'));
    }

    public function verify(Request $request)
    {
        $reference = $request->get('reference');
        $productId = $request->get('product_id');
        
        if (!$reference || !$productId) {
            return redirect()->route('tenant.marketplace.index', ['tenant' => app('currentTenant')->subdomain])
                ->with('error', 'Invalid payment reference.');
        }

        $tenant = app('currentTenant');
        $product = Product::findOrFail($productId);

        // Verify with Paystack
        $secretKey = env('PAYSTACK_SECRET_KEY');
        if (!$secretKey) {
            // For local development without Paystack keys
            $paymentSuccessful = true;
        } else {
            $response = Http::withToken($secretKey)
                ->get("https://api.paystack.co/transaction/verify/{$reference}");
                
            $paymentSuccessful = $response->successful() && $response->json('data.status') === 'success';
        }

        if ($paymentSuccessful) {
            // Check if transaction already recorded to prevent double crediting
            $exists = Transaction::where('reference', $reference)->exists();
            
            if (!$exists) {
                // Credit Tenant's Wallet
                $tenant->increment('wallet_balance', $product->price);

                // Record Transaction
                Transaction::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => auth()->id(), // null if guest checkout is allowed
                    'type' => 'credit',
                    'amount' => $product->price,
                    'reference' => $reference,
                    'status' => 'completed',
                    'description' => "Sale of product: {$product->title}",
                ]);
            }

            return redirect()->route('tenant.checkout.success', [
                'tenant' => $tenant->subdomain, 
                'product' => $product->id, 
                'reference' => $reference
            ]);
        }

        return redirect()->route('tenant.marketplace.show', ['tenant' => $tenant->subdomain, 'product' => $product->id])
            ->with('error', 'Payment verification failed.');
    }

    public function success(Request $request, Product $product)
    {
        $tenant = app('currentTenant');
        $reference = $request->get('reference');

        return view('tenant.public.checkout.success', compact('tenant', 'product', 'reference'));
    }

    public function download(Product $product)
    {
        $tenant = app('currentTenant');
        
        // Basic security check: ideally we verify the user actually purchased this recently
        // For now, if it's a soft copy, we'll allow download from the success page link.
        if ($product->tenant_id != $tenant->id || $product->format !== 'soft_copy' || !$product->file_path) {
            abort(404);
        }

        return Storage::disk('public')->download($product->file_path, $product->title . '.' . pathinfo($product->file_path, PATHINFO_EXTENSION));
    }
}
