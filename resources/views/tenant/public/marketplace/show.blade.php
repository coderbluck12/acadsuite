@extends('layouts.public')

@section('content')
    <div class="container my-5 pt-5">
        <div class="mb-4">
            <a href="{{ route('tenant.marketplace.index', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none border rounded-pill px-3 py-1 bg-light text-dark">
                <i class="bi bi-arrow-left me-1"></i> Back to Store
            </a>
        </div>
        
        <div class="row g-5">
            <div class="col-md-5">
                <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/publications.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $product->title }}" style="max-height: 500px; object-fit: cover;">
            </div>
            
            <div class="col-md-7">
                <span class="badge {{ $product->format === 'hard_copy' ? 'bg-secondary' : 'bg-info text-dark' }} mb-2">
                    {{ $product->format === 'hard_copy' ? 'Hard Copy Book' : 'Soft Copy (Downloadable)' }}
                </span>
                <h2 class="fw-bold mb-3 text-dark">{{ $product->title }}</h2>
                
                <h3 class="fw-bold text-primary mb-4 border-bottom pb-3">₦{{ number_format($product->price, 2) }}</h3>
                
                <div class="mb-5">
                    <h5 class="fw-semibold mb-3">Description</h5>
                    <p class="text-muted" style="line-height: 1.8;">
                        {!! nl2br(e($product->description)) !!}
                    </p>
                </div>
                
                <div class="d-grid mt-4">
                    <a href="{{ route('tenant.checkout.index', ['tenant' => $tenant->subdomain, 'product' => $product->id]) }}" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow">
                        <i class="bi bi-cart-check me-2"></i> Buy Now
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
