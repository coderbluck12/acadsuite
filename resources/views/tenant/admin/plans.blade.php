@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="mb-4">
        <h4 class="fw-bold">Plans & Billing</h4>
        <p class="text-muted text-sm">Manage your academic suite subscription and view usage limits.</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- Current Plan Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-header bg-{{ $tenant->plan === 'pro' ? 'primary' : 'dark' }} text-white py-3 border-0">
                    <h6 class="text-uppercase small fw-bold mb-0 opacity-75">Your Current Plan</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="display-5 fw-bold mb-1 text-{{ $tenant->plan === 'pro' ? 'primary' : 'dark' }}">
                        {{ $tenant->plan === 'pro' ? 'Pro' : 'Free' }}
                    </div>
                    <div class="fs-4 text-muted mb-4">
                        {{ $tenant->plan === 'pro' ? '$50' : '$0' }}<span class="fs-6">/month</span>
                    </div>
                    
                    @if($tenant->plan === 'pro')
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i> Active Subscription
                        </span>
                        <p class="small text-muted mb-0">Unlimited uploads enabled.</p>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill mb-3">
                            Standard Tier
                        </span>
                        <p class="small text-muted mb-4 px-3">Upgrade to Pro for unlimited courses, blogs, and student resources.</p>
                        <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm" onclick="alert('Upgrade request sent to SuperAdmin! They will contact you shortly.')">
                            Upgrade to Pro
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Usage Statistics Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-graph-up me-2"></i> Usage & Limits</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-4 mt-1">
                        @php
                            $limit = 5;
                            $items = [
                                ['label' => 'Courses', 'count' => $stats['courses'], 'icon' => 'book'],
                                ['label' => 'Blogs', 'count' => $stats['blogs'], 'icon' => 'journal-text'],
                                ['label' => 'Publications', 'count' => $stats['publications'], 'icon' => 'newspaper'],
                                ['label' => 'Resources', 'count' => $stats['resources'], 'icon' => 'folder'],
                                ['label' => 'Assignments', 'count' => $stats['assignments'], 'icon' => 'file-earmark-check'],
                            ];
                        @endphp

                        @foreach($items as $item)
                        <div class="col-md-6 col-xl-4">
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle bg-white shadow-sm p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-{{ $item['icon'] }} text-primary"></i>
                                    </div>
                                    <span class="fw-bold small">{{ $item['label'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-end">
                                    <div class="fs-4 fw-bold">{{ $item['count'] }}</div>
                                    <div class="text-muted small mb-1">
                                        @if($tenant->plan === 'pro')
                                            Unlimited
                                        @else
                                            {{ $item['count'] }}/{{ $limit }} used
                                        @endif
                                    </div>
                                </div>
                                @if($tenant->plan !== 'pro')
                                    <div class="progress mt-2" style="height: 6px;">
                                        @php $percent = ($item['count'] / $limit) * 100; @endphp
                                        <div class="progress-bar bg-{{ $percent >= 100 ? 'danger' : ($percent >= 80 ? 'warning' : 'primary') }}" 
                                             role="progressbar" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tier Comparison -->
    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-header bg-light py-3 border-0">
            <h6 class="fw-bold mb-0">Plan Comparison</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Features</th>
                        <th class="text-center py-3" style="width: 25%;">Free ($0/mo)</th>
                        <th class="text-center py-3 bg-primary bg-opacity-10 text-primary fw-bold" style="width: 25%;">Pro ($50/mo)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">Max Courses / Blogs</td>
                        <td class="text-center text-muted">5 each</td>
                        <td class="text-center fw-bold"><i class="bi bi-infinity me-1"></i> Unlimited</td>
                    </tr>
                    <tr>
                        <td class="ps-4">Resource Storage</td>
                        <td class="text-center text-muted">Standard</td>
                        <td class="text-center fw-bold text-success border-start border-primary border-opacity-10">Premium High-Speed</td>
                    </tr>
                    <tr>
                        <td class="ps-4">Custom Domain Portat</td>
                        <td class="text-center text-muted"><i class="bi bi-x-circle text-danger"></i></td>
                        <td class="text-center fw-bold text-success border-start border-primary border-opacity-10"><i class="bi bi-check-circle-fill"></i> Available</td>
                    </tr>
                    <tr>
                        <td class="ps-4">Priority Support</td>
                        <td class="text-center text-muted"><i class="bi bi-x-circle text-danger"></i></td>
                        <td class="text-center fw-bold text-success border-start border-primary border-opacity-10"><i class="bi bi-check-circle-fill"></i> 24/7 Access</td>
                    </tr>
                    <tr>
                        <td class="ps-4">White-labeling</td>
                        <td class="text-center text-muted"><i class="bi bi-x-circle text-danger"></i></td>
                        <td class="text-center fw-bold text-success border-start border-primary border-opacity-10"><i class="bi bi-check-circle-fill"></i> Fully Rebrand</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
