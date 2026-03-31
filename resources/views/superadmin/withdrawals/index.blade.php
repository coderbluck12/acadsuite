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
        <h4 class="mb-0">Withdrawal Requests</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $withdrawal->tenant->name ?? 'Unknown' }}</div>
                                <div class="text-muted small">{{ $withdrawal->tenant->owner_name ?? '' }}</div>
                            </td>
                            <td class="fw-bold text-danger">NGN {{ number_format($withdrawal->amount, 2) }}</td>
                            <td class="small text-muted">{{ $withdrawal->description }}</td>
                            <td class="small">{{ $withdrawal->created_at->format('d M Y h:i A') }}</td>
                            <td>
                                @if($withdrawal->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($withdrawal->status === 'pending')
                                    <form method="POST" action="{{ route('superadmin.withdrawals.approve', $withdrawal) }}" class="d-inline" onsubmit="return confirm('Mark this withdrawal as completed/paid?')">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">Mark Paid</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No withdrawal requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($withdrawals->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
