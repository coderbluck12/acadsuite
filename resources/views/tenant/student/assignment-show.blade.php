@extends('layouts.admin')

@section('sidebar-nav')
<nav>
    <a href="{{ route('tenant.student.dashboard', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.dashboard') ? 'active' : '' }} m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="{{ route('tenant.student.courses', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.courses') ? 'active' : '' }} m-2">
        <i class="bi bi-book me-2"></i> Courses
    </a>
    <a href="{{ route('tenant.student.assignments', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.assignments*') ? 'active' : '' }} m-2">
        <i class="bi bi-file-earmark-text me-2"></i> Assignments
    </a>
    <a href="{{ route('tenant.student.notifications', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.notifications') ? 'active' : '' }} m-2">
        <i class="bi bi-bell me-2"></i> Notifications
    </a>
    <a href="{{ route('tenant.marketplace.index', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.marketplace*') ? 'active' : '' }} m-2">
        <i class="bi bi-shop me-2"></i> Store
    </a>
    <hr class="border-white opacity-25 mx-3">
    <a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="m-2" target="_blank">
        <i class="bi bi-box-arrow-up-right me-2"></i> Portal Home
    </a>
    <form method="POST" action="{{ route('tenant.logout', ['tenant' => $tenant->subdomain]) }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-2">
    {{-- Back button --}}
    <div class="mb-3">
        <a href="{{ route('tenant.student.assignments', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back to Assignments
        </a>
    </div>

    <div class="row g-4">
        {{-- Left column: Assignment Details --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    {{-- Title & Status --}}
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h4 class="fw-bold text-primary mb-0">{{ $assignment->title }}</h4>
                        @if($submission)
                            @if($submission->status === 'graded')
                                <span class="badge bg-primary fs-6"><i class="bi bi-award me-1"></i>Graded</span>
                            @else
                                <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Submitted</span>
                            @endif
                        @else
                            @if($assignment->due_date && $assignment->due_date->isPast())
                                <span class="badge bg-danger fs-6"><i class="bi bi-exclamation-circle me-1"></i>Overdue</span>
                            @else
                                <span class="badge bg-warning text-dark fs-6"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                            @endif
                        @endif
                    </div>

                    {{-- Meta --}}
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @if($assignment->course)
                        <span class="text-muted small">
                            <i class="bi bi-book me-1 text-primary"></i>{{ $assignment->course->title }}
                        </span>
                        @endif
                        @if($assignment->due_date)
                        <span class="text-muted small">
                            <i class="bi bi-calendar-event me-1 {{ $assignment->due_date->isPast() && !$submission ? 'text-danger' : 'text-secondary' }}"></i>
                            Due: {{ $assignment->due_date->format('d M Y') }}
                        </span>
                        @else
                        <span class="text-muted small"><i class="bi bi-calendar me-1"></i>No due date</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    <h6 class="fw-semibold text-secondary mb-2">Description / Instructions</h6>
                    <div class="p-3 bg-light rounded mb-4" style="white-space: pre-wrap; line-height: 1.7;">{{ $assignment->description ?: 'No description provided.' }}</div>

                    {{-- Attachment --}}
                    @if($assignment->file_path)
                    <div class="d-flex align-items-center gap-3 p-3 border rounded bg-white">
                        <i class="bi bi-paperclip fs-4 text-secondary"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Assignment Material</div>
                            <small class="text-muted">Click to download the attached file</small>
                        </div>
                        <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" download class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right column: Submission --}}
        <div class="col-lg-5">
            @if($submission)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-{{ $submission->status === 'graded' ? 'primary' : 'success' }} text-white">
                    <i class="bi bi-{{ $submission->status === 'graded' ? 'award' : 'check-circle' }} me-2"></i>
                    {{ $submission->status === 'graded' ? 'Assignment Graded' : 'Submission Received' }}
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2 small">Submitted {{ $submission->created_at->diffForHumans() }} ({{ $submission->created_at->format('d M Y, h:i A') }})</p>
                    @if($submission->comment)
                    <div class="mb-3">
                        <strong>Your comment:</strong>
                        <p class="text-muted small mb-0">{{ $submission->comment }}</p>
                    </div>
                    @endif
                    @if($submission->file_path)
                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" download class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="bi bi-download me-1"></i> View My Submission
                    </a>
                    @endif
                </div>
            </div>
            @else
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-upload me-2"></i> Submit Your Work
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('tenant.student.assignments.submit', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                            <small class="text-muted">Max file size: 20MB</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Additional Comments</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Add any notes for the instructor..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-upload me-1"></i> Submit Assignment
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
