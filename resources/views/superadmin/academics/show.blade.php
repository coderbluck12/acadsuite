@extends('layouts.admin')

@section('sidebar-nav')
<nav>
    <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }} m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="{{ route('superadmin.academics.index') }}" class="{{ request()->routeIs('superadmin.academics*') ? 'active' : '' }} m-2">
        <i class="bi bi-people me-2"></i> All Academics
    </a>
    <a href="{{ route('superadmin.withdrawals.index') }}" class="{{ request()->routeIs('superadmin.withdrawals*') ? 'active' : '' }} m-2">
        <i class="bi bi-cash-stack me-2"></i> Withdrawals
    </a>
    <hr class="border-white opacity-25 mx-3">
    <form method="POST" action="{{ route('superadmin.logout') }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Academic Suite Details: {{ $tenant->name }}</h4>
        <a href="{{ route('superadmin.academics.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back to Academics
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-0 m-0">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">Owner Information</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $tenant->owner_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $tenant->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $tenant->phone ?? 'Not Provided' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-0 m-0">
                <div class="card-body">
                    <h5 class="fw-bold text-success mb-3">Portal Information</h5>
                    <p class="mb-1"><strong>Suite Name:</strong> {{ $tenant->name }}</p>
                    <p class="mb-1">
                        <strong>Subdomain:</strong> 
                        <a href="http://{{ $tenant->subdomain }}.{{ config('app.base_domain') }}" target="_blank">{{ $tenant->subdomain }}.{{ config('app.base_domain') }}</a>
                    </p>
                    <p class="mb-0"><strong>Registered On:</strong> {{ $tenant->created_at->format('j M Y, g:i A') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-0 m-0">
                <div class="card-body">
                    <h5 class="fw-bold text-warning mb-3">Statistics & Status</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Students:</span>
                        <span class="badge bg-primary fs-6">{{ $studentCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Current Plan:</span>
                        <span class="badge bg-{{ $tenant->plan === 'pro' ? 'success' : 'info text-dark' }} fs-6">
                            {{ ucfirst($tenant->plan ?: 'free') }}
                        </span>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Manage Portal</label>
                        <div class="d-flex gap-2 mb-2">
                             <form method="POST" action="{{ route('superadmin.academics.toggle', $tenant) }}" class="flex-grow-1">
                                 @csrf @method('PATCH')
                                 <button class="btn btn-sm w-100 {{ $tenant->is_active ? 'btn-outline-warning text-dark' : 'btn-outline-success' }}">
                                     {{ $tenant->is_active ? 'Deactivate' : 'Activate' }}
                                 </button>
                             </form>
                             <form method="POST" action="{{ route('superadmin.academics.update-plan', ['tenant' => $tenant, 'plan' => $tenant->plan === 'pro' ? 'free' : 'pro']) }}" class="flex-grow-1">
                                 @csrf @method('PATCH')
                                 <button class="btn btn-sm w-100 {{ $tenant->plan === 'pro' ? 'btn-outline-info' : 'btn-outline-primary' }}">
                                     {{ $tenant->plan === 'pro' ? 'Downgrade to Free' : 'Upgrade to Pro' }}
                                 </button>
                             </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
