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
        <h4 class="mb-0">All Academics</h4>
        <span class="badge bg-primary rounded-pill fs-6">{{ $academics->total() }} Total</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="academicsTable" class="table align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Suite Name / Owner</th>
                            <th>Portal URL</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($academics as $i => $academic)
                        <tr>
                            <td>{{ $academics->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold">{{ $academic->name }}</div>
                                <div class="text-muted small">{{ $academic->owner_name }}</div>
                            </td>
                            <td><a href="http://{{ $academic->subdomain }}.{{ config('app.base_domain') }}" target="_blank" class="text-decoration-none small"><code>{{ $academic->subdomain }}.{{ config('app.base_domain') }}</code></a></td>
                            <td class="small">{{ $academic->email }}</td>
                            <td>
                                @if($academic->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $academic->users()->where('role','student')->count() }}</td>
                            <td class="small">{{ $academic->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('superadmin.academics.show', $academic) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <form method="POST" action="{{ route('superadmin.academics.toggle', $academic) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $academic->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                            {{ $academic->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('superadmin.academics.destroy', $academic) }}" class="d-inline" onsubmit="return confirm('Delete this portal? All data will be lost.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-5">No academics registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($academics->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $academics->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
