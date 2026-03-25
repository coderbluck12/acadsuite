@extends('layouts.public')

@section('content')
<div class="container my-5 pt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width: 100px; height: 100px;">
                    <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                </div>
            </div>
            
            <h2 class="fw-bold mb-3">Payment Successful!</h2>
            <p class="text-muted fs-5 mb-4">Thank you for purchasing <strong>{{ $product->title }}</strong>.</p>
            
            <div class="card border-0 bg-light rounded-4 mb-4">
                <div class="card-body p-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Transaction Ref:</span>
                        <span class="fw-bold text-dark">{{ $reference }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Amount Paid:</span>
                        <span class="fw-bold text-dark">₦{{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($product->format === 'soft_copy')
                <div class="alert alert-success mt-4 py-3">
                    <p class="mb-2"><i class="bi bi-arrow-down-circle-fill me-2 fs-5"></i>Your digital product is ready for download.</p>
                    <a href="{{ route('tenant.checkout.download', ['tenant' => $tenant->subdomain, 'product' => $product->id]) }}" class="btn btn-success fw-bold px-4 rounded-pill">
                        Download Soft Copy
                    </a>
                </div>
            @else
                <div class="alert alert-info mt-4 py-3">
                    <i class="bi bi-box-seam me-2"></i> This is a physical book. The school/administration will process your order and provide delivery or pick-up instructions.
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('tenant.marketplace.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-primary rounded-pill px-4">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
