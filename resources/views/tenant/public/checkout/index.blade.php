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
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/publications.jpg') }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
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
        
        let email = document.getElementById('email-address').value;
        if (!email) {
            alert('Please enter an email address.');
            return;
        }

        var customRef = 'PKG_' + Math.floor((Math.random() * 1000000000) + 1);
        let handler = PaystackPop.setup({
            key: '{{ env("PAYSTACK_PUBLIC_KEY", "pk_test_placeholder") }}', 
            email: email,
            amount: {{ $product->price * 100 }}, 
            currency: "NGN",
            reference: customRef, 
            callback: function(response) {
                let reference = response.reference || response.trxref || response.trans || customRef;
                let baseUrl = "{{ route('tenant.checkout.verify', ['tenant' => $tenant->subdomain]) }}";
                let separator = baseUrl.indexOf('?') !== -1 ? '&' : '?';
                let verifyUrl = baseUrl + separator + "reference=" + encodeURIComponent(reference) + "&product_id={{ $product->id }}&email=" + encodeURIComponent(email);
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
