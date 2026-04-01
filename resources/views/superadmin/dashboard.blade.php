@extends('layouts.admin')

@section('sidebar-nav')
    @include('superadmin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <h4 class="mb-4">Pentagonware — HQ Dashboard</h4>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="bi bi-people-fill fs-4 text-primary"></i></div>
                    <div><div class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</div><div class="text-muted small">Total Academics</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="bi bi-check-circle-fill fs-4 text-success"></i></div>
                    <div><div class="fs-2 fw-bold text-success">{{ $stats['active'] }}</div><div class="text-muted small">Active Portals</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="bi bi-x-circle-fill fs-4 text-danger"></i></div>
                    <div><div class="fs-2 fw-bold text-danger">{{ $stats['inactive'] }}</div><div class="text-muted small">Inactive Portals</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3"><i class="bi bi-calendar-plus-fill fs-4 text-warning"></i></div>
                    <div><div class="fs-2 fw-bold text-warning">{{ $stats['thisMonth'] }}</div><div class="text-muted small">New This Month</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Academics --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Recent Registrations</h5>
            <a href="{{ route('superadmin.academics.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Academic / Owner</th>
                            <th>Subdomain</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAcademics as $academic)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $academic->name }}</div>
                                <div class="text-muted small">{{ $academic->owner_name }}</div>
                            </td>
                            <td><code>{{ $academic->subdomain }}.{{ config('app.base_domain') }}</code></td>
                            <td>{{ $academic->email }}</td>
                            <td>
                                @if($academic->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $academic->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('superadmin.academics.show', $academic) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                <form method="POST" action="{{ route('superadmin.academics.toggle', $academic) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $academic->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        {{ $academic->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No academics registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
