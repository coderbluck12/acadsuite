<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Purchase;
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
        $email = $request->get('email'); // Can be passed from frontend
        
        \Illuminate\Support\Facades\Log::info("Checkout Verify Started", ['ref' => $reference, 'product_id' => $productId, 'email' => $email]);

        if (!$reference || !$productId) {
            return redirect()->route('tenant.marketplace.index', ['tenant' => app('currentTenant')->subdomain])
                ->with('error', 'Invalid payment reference.');
        }

        $tenant = app('currentTenant');
        $product = Product::findOrFail($productId);

        // Verify with Paystack or Simulator
        $paymentSuccessful = false;
        
        if ($request->has('simulate_success')) {
            $paymentSuccessful = true;
            \Illuminate\Support\Facades\Log::info("Simulated Checkout Success");
        } elseif ($request->has('simulate_failure')) {
            $paymentSuccessful = false;
            \Illuminate\Support\Facades\Log::info("Simulated Checkout Failure");
        } else {
            $secretKey = env('PAYSTACK_SECRET_KEY');
            if (!$secretKey) {
                $paymentSuccessful = true;
                \Illuminate\Support\Facades\Log::info("Checkout Verify bypass (No Secret Key)");
            } else {
                $response = Http::withToken($secretKey)
                    ->get("https://api.paystack.co/transaction/verify/{$reference}");
                    
                $paymentSuccessful = $response->successful() && $response->json('data.status') === 'success';
                \Illuminate\Support\Facades\Log::info("Paystack Verify API", ['status' => $paymentSuccessful, 'body' => $response->json()]);
            }
        }

        if ($paymentSuccessful) {
            try {
                // If email wasn't provided, try to extract from Paystack response
                if (!$email && isset($response) && $response->successful()) {
                    $email = $response->json('data.customer.email') ?? 'guest@example.com';
                } elseif (!$email) {
                    $email = auth()->check() ? auth()->user()->email : 'guest@example.com';
                }
                
                // Track the specific product purchase
                $purchase = Purchase::firstOrCreate(
                    ['reference' => $reference],
                    [
                        'tenant_id' => $tenant->id,
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'buyer_email' => $email,
                        'amount' => $product->price,
                    ]
                );

                // Check if transaction already recorded to prevent double crediting
                $exists = Transaction::where('reference', $reference)->exists();
                
                if (!$exists) {
                    $feePercentage = \App\Models\PlatformSetting::get('platform_fee_percentage', 0);
                    $feeAmount = $product->price * ($feePercentage / 100);
                    $tenantEarnings = $product->price - $feeAmount;

                    // Credit Tenant's Wallet
                    $tenant->increment('wallet_balance', $tenantEarnings);

                    // Record Transaction
                    Transaction::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => auth()->id(),
                        'type' => 'credit',
                        'amount' => $tenantEarnings,
                        'reference' => $reference,
                        'status' => 'completed',
                        'description' => "Sale of product: {$product->title}" . ($feeAmount > 0 ? " (Platform Fee Deducted)" : ""),
                    ]);

                    if ($feeAmount > 0) {
                        Transaction::create([
                            'tenant_id' => $tenant->id,
                            'user_id' => auth()->id(),
                            'type' => 'platform_fee',
                            'amount' => $feeAmount,
                            'reference' => $reference,
                            'status' => 'completed',
                            'description' => "Platform Fee for product: {$product->title}",
                        ]);
                    }
                }

                \Illuminate\Support\Facades\Log::info("Checkout Verify Success Database Operations Completed", ['purchase_id' => $purchase->id]);

                return redirect()->route('tenant.checkout.success', [
                    'tenant' => $tenant->subdomain, 
                    'product' => $product->id, 
                    'purchase' => $purchase->id,
                    'reference' => $reference
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Checkout Verify DB Error", ['msg' => $e->getMessage()]);
                // If it fails, log the error but we might still want to let them know
                return redirect()->route('tenant.marketplace.show', ['tenant' => $tenant->subdomain, 'product' => $product->id])
                    ->with('error', 'Payment succeeded but failed to record the transaction securely. Please contact support.');
            }
        }

        return redirect()->route('tenant.marketplace.show', ['tenant' => $tenant->subdomain, 'product' => $product->id])
            ->with('error', 'Payment verification failed.');
    }

    public function success(Request $request, Product $product)
    {
        $tenant = app('currentTenant');
        $reference = $request->get('reference');
        $purchaseId = $request->get('purchase');
        $purchase = $purchaseId ? Purchase::find($purchaseId) : null;

        return view('tenant.public.checkout.success', compact('tenant', 'product', 'reference', 'purchase'));
    }

    public function receipt(Purchase $purchase)
    {
        $tenant = app('currentTenant');
        
        // Security check
        if ($purchase->tenant_id != $tenant->id) {
            abort(404);
        }

        return view('tenant.public.checkout.receipt', compact('tenant', 'purchase'));
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
