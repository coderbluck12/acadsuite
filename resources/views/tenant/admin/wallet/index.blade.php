@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Wallet & Payments</h4>
    </div>
    
    <div class="row mb-4">
        <!-- Balance Card -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center py-5">
                    <h5 class="card-title text-uppercase opacity-75">Available Balance</h5>
                    <h1 class="display-4 fw-bold mb-3">₦{{ number_format($tenant->wallet_balance, 2) }}</h1>
                    
                    @if($tenant->wallet_balance >= 100)
                        <button class="btn btn-light rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                            <i class="bi bi-wallet2 me-2"></i> Request Withdrawal
                        </button>
                    @else
                        <button class="btn btn-light rounded-pill px-4 shadow-sm" disabled title="Minimum withdrawal is ₦100">
                            <i class="bi bi-wallet2 me-2"></i> Request Withdrawal
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Bank Details Info -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-bank me-2"></i> Withdrawal Details
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Your funds will be manually confirmed and sent to the following account upon withdrawal request.</p>
                    
                    @if($tenant->account_number)
                        <p class="mb-1"><strong>Bank:</strong> {{ $tenant->bank_name }}</p>
                        <p class="mb-1"><strong>Account No:</strong> {{ $tenant->account_number }}</p>
                        <p class="mb-0"><strong>Account Name:</strong> {{ $tenant->account_name }}</p>
                    @else
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> No bank details saved yet. You will be prompted to enter them during withdrawal.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card shadow-sm p-4">
        <h5 class="mb-3">Transaction History</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td>{{ $tx->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            @if($tx->type === 'credit')
                                <span class="badge bg-success"><i class="bi bi-arrow-down-left"></i> Credit</span>
                            @elseif($tx->type === 'debit')
                                <span class="badge bg-danger"><i class="bi bi-arrow-up-right"></i> Debit</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-cash-coin"></i> Withdrawal</span>
                            @endif
                        </td>
                        <td class="fw-bold {{ $tx->type === 'credit' ? 'text-success' : 'text-danger' }}">
                            {{ $tx->type === 'credit' ? '+' : '-' }}₦{{ number_format($tx->amount, 2) }}
                        </td>
                        <td>{{ $tx->description ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $tx->status === 'completed' ? 'bg-success' : ($tx->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-wallet2 me-2"></i> Request Withdrawal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tenant.admin.wallet.withdraw', ['tenant' => $tenant->subdomain]) }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-info py-2">
                        Available Balance: <strong>₦{{ number_format($tenant->wallet_balance, 2) }}</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount to Withdraw (₦)</label>
                        <input type="number" name="amount" class="form-control" min="100" max="{{ $tenant->wallet_balance }}" step="0.01" required>
                    </div>

                    <h6 class="mt-4 mb-3 border-bottom pb-2">Bank Details</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ $tenant->bank_name }}" required placeholder="e.g. GTBank, First Bank">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Account Number</label>
                        <input type="text" name="account_number" class="form-control" value="{{ $tenant->account_number }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Account Name</label>
                        <input type="text" name="account_name" class="form-control" value="{{ $tenant->account_name }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">Submit Request</button>
                    <small class="text-muted d-block text-center mt-2">Note: Withdrawals are processed manually.</small>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
