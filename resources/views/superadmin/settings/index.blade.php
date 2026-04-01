@extends('layouts.admin')

@section('sidebar-nav')
    @include('superadmin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-4">
    <h3 class="mb-4 fw-bold">Platform Settings</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white pb-0 border-0 pt-4 px-4">
            <h5 class="fw-bold"><i class="bi bi-gear me-2"></i> Global Configuration</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4" style="max-width: 400px;">
                    <label class="form-label fw-semibold">Platform Fee Percentage (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.1" min="0" max="100" class="form-control" name="platform_fee_percentage" value="{{ $fee_percentage }}" required>
                        <span class="input-group-text bg-light">%</span>
                    </div>
                    <small class="text-muted d-block mt-2">
                        This percentage will be automatically deducted from all Tenant storefront and consultation sales. Earnings are retained by the platform, while the remaining balance goes to the Tenant's digital wallet.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
