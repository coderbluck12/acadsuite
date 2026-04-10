@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Welcome back, {{ $tenant->owner_name }} 👋</h4>
            <small class="text-muted">{{ $tenant->name }} &mdash; Admin Dashboard</small>
        </div>
        <a href="{{ route('tenant.admin.profile', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-person-circle me-1"></i> Profile Settings
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.students', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-primary">{{ $totalStudents }}</div>
                            <div class="text-muted small">Total Students</div>
                        </div>
                    </div>
                    @if($pendingStudents > 0)
                    <div class="card-footer bg-warning bg-opacity-10 border-0 py-1 px-3">
                        <small class="text-warning fw-semibold"><i class="bi bi-clock me-1"></i>{{ $pendingStudents }} pending approval</small>
                    </div>
                    @endif
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.courses.index', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-book-fill fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-success">{{ $totalCourses }}</div>
                            <div class="text-muted small">Courses</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.publications.index', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-journal-text fs-4 text-info"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-info">{{ $totalPublications }}</div>
                            <div class="text-muted small">Publications</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.assignments.index', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-file-earmark-text-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-warning">{{ $totalAssignments }}</div>
                            <div class="text-muted small">Assignments</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.resources.index', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-folder2-open fs-4 text-danger"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-danger">{{ $totalResources }}</div>
                            <div class="text-muted small">Resources</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.wallet', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-wallet2 fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-success">₦{{ number_format($walletBalance, 0) }}</div>
                            <div class="text-muted small">Wallet Balance</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('tenant.admin.plans', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-star-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold text-warning text-uppercase">{{ $tenant->plan ?? 'free' }}</div>
                            <div class="text-muted small">Current Plan</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people me-2 text-primary"></i>Recent Students</span>
                    <a href="{{ route('tenant.admin.students', ['tenant' => $tenant->subdomain]) }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentStudents as $student)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $student->name }}</div>
                            <small class="text-muted">{{ $student->email }}</small>
                        </div>
                        <span class="badge {{ $student->status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="list-group-item text-muted text-center py-3">No students registered yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text me-2 text-warning"></i>Recent Assignments</span>
                    <a href="{{ route('tenant.admin.assignments.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentAssignments as $assignment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $assignment->title }}</div>
                            <small class="text-muted">{{ $assignment->course?->title ?? 'General' }}</small>
                        </div>
                        <span class="badge {{ $assignment->is_published ? 'bg-success' : 'bg-secondary' }}">
                            {{ $assignment->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    @empty
                    <div class="list-group-item text-muted text-center py-3">No assignments created yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-card { transition: transform 0.15s ease, box-shadow 0.15s ease; }
.hover-card:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.1) !important; }
</style>
@endpush
