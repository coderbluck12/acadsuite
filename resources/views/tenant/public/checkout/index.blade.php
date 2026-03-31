@extends('layouts.public')

@section('content')
<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-lock-fill me-2"></i> Secure Checkout</h4>
                </div>
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="d-flex align-items-center">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-secondary" style="width: 50px; height: 50px;">
                                    <i class="bi bi-book"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-bold">{{ Str::limit($product->title, 40) }}</h6>
                                <span class="badge bg-light text-dark border">{{ $product->format === 'hard_copy' ? 'Hard Copy' : 'Soft Copy' }}</span>
                            </div>
                        </div>
                        <h5 class="fw-bold m-0 text-primary">₦{{ number_format($product->price, 2) }}</h5>
                    </div>

                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" id="email-address" class="form-control py-2" required placeholder="Enter email for receipt" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Contact Phone</label>
                            <input type="text" id="phone" class="form-control py-2" required placeholder="Enter phone number">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" onclick="payWithPaystack(event)" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm">
                                Pay ₦{{ number_format($product->price, 2) }}
                            </button>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('tenant.marketplace.show', ['tenant' => $tenant->subdomain, 'product' => $product->id]) }}" class="text-muted text-decoration-none small">Cancel and return to product</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted small">
                <p><i class="bi bi-shield-check text-success me-1"></i> Payments are securely processed by Paystack.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    function payWithPaystack(e) {
        e.preventDefault();
        
        let handler = PaystackPop.setup({
            key: '{{ env("PAYSTACK_PUBLIC_KEY") }}', // Put Paystack Public Key in env
            email: document.getElementById('email-address').value,
            amount: {{ $product->price * 100 }}, // Paystack takes amount in kobo
            currency: "NGN",
            ref: ''+Math.floor((Math.random() * 1000000000) + 1), // Generate a random ref
            callback: function(response) {
                // Payment was successful, verify on backend
                let reference = response.reference;
                let email = document.getElementById('email-address').value;
                let verifyUrl = "{{ route('tenant.checkout.verify', ['tenant' => $tenant->subdomain]) }}?reference=" + reference + "&product_id={{ $product->id }}&email=" + encodeURIComponent(email);
                window.location.href = verifyUrl;
            },
            onClose: function() {
                alert('Payment window closed.');
            }
        });

        handler.openIframe();
    }
</script>
@endsection
