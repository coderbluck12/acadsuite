@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('title', 'Marketplace Purchases')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Marketplace Purchases</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Buyer Information</th>
                        <th>Product Details</th>
                        <th>Reference</th>
                        <th class="text-end pe-4">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr>
                        <td class="ps-4 text-muted small">
                            {{ $purchase->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $purchase->buyer_email }}</div>
                                    @if($purchase->user)
                                        <div class="small text-muted">{{ $purchase->user->name }}</div>
                                    @else
                                        <div class="small text-muted">Guest Purchaser</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($purchase->product)
                                <div class="fw-medium text-dark">{{ Str::limit($purchase->product->title, 40) }}</div>
                                <span class="badge bg-light text-secondary border">{{ $purchase->product->format === 'hard_copy' ? 'Hard Copy' : 'Soft Copy' }}</span>
                            @else
                                <span class="text-muted">Product Deleted</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted font-monospace small">{{ $purchase->reference }}</span>
                        </td>
                        <td class="text-end pe-4 fw-bold text-success">
                            ₦{{ number_format($purchase->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-cart-x fs-1 d-block mb-3 opacity-50"></i>
                            <p class="mb-0 fs-5">No purchases have been made yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($purchases->hasPages())
    <div class="card-footer bg-white py-3 border-0">
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection
