@extends('layouts.public')

@section('content')
<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white text-center py-4 border-bottom">
                    <!-- Brand/Logo -->
                    <div class="mt-2 mb-3">
                        @if($tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" style="max-height: 50px;">
                        @else
                            <h4 class="fw-bold mb-0 text-primary">{{ $tenant->name }}</h4>
                        @endif
                    </div>
                    
                    <h5 class="text-uppercase text-muted fw-bold tracking-wide">Purchase Receipt</h5>
                </div>
                
                <div class="card-body p-5">
                    <div class="row mb-5">
                        <div class="col-sm-6">
                            <h6 class="text-muted mb-1">Receipt To:</h6>
                            <p class="fw-bold mb-0">{{ $purchase->buyer_email }}</p>
                            @if($purchase->user)
                                <p class="text-muted small">{{ $purchase->user->name }}</p>
                            @endif
                        </div>
                        <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                            <h6 class="text-muted mb-1">Receipt Details:</h6>
                            <p class="mb-0"><strong>Ref:</strong> {{ $purchase->reference }}</p>
                            <p class="mb-0"><strong>Date:</strong> {{ $purchase->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless border-bottom">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Item Description</th>
                                    <th class="py-3 text-end">Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-4">
                                        <div class="d-flex align-items-center">
                                            @if($purchase->product->image_path)
                                                <img src="{{ asset('storage/' . $purchase->product->image_path) }}" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-secondary" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-book"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $purchase->product->title }}</div>
                                                <div class="small text-muted">{{ $purchase->product->format === 'hard_copy' ? 'Hard Copy Book' : 'Digital Download' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-end align-middle fw-bold fs-5">
                                        ₦{{ number_format($purchase->amount, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-5">
                        <button onclick="window.print()" class="btn btn-primary px-4 py-2 me-2">
                            <i class="bi bi-printer me-2"></i> Print Receipt
                        </button>
                        <a href="{{ route('tenant.checkout.success', ['tenant' => $tenant->subdomain, 'product' => $purchase->product_id, 'reference' => $purchase->reference, 'purchase' => $purchase->id]) }}" class="btn btn-outline-secondary px-4 py-2">
                            Return to Instructions
                        </a>
                    </div>
                </div>
                
                <div class="card-footer bg-light text-center py-4 border-0 rounded-bottom-4">
                    <p class="small text-muted mb-0">Thank you for your purchase from {{ $tenant->name }}.</p>
                    <p class="small text-muted mb-0">If you have any questions, please contact {{ $tenant->email ?? 'support' }}.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background-color: #fff !important; }
    .container { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
    .btn, .navbar, .footer, head title { display: none !important; }
}
</style>
@endsection
