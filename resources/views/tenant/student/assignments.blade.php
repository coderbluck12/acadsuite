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
    <a href="{{ route('tenant.resources', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->is('resources*') ? 'active' : '' }} m-2">
        <i class="bi bi-folder2-open me-2"></i> Resources
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
    <h4 class="mb-4">Assignments</h4>

    <div class="row g-4">
        @forelse($assignments as $assignment)
            @php
                $submission = $assignment->submissions->first();
                $isSubmitted = $submission !== null;
                $isPastDue = $assignment->due_date && $assignment->due_date->isPast();
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card shadow border-0 h-100 p-0 m-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary">{{ $assignment->title }}</h5>
                        <p class="card-text text-muted small mb-3">{{ Str::limit($assignment->description, 100) }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @if($assignment->due_date)
                                    <span class="badge {{ $isPastDue && !$isSubmitted ? 'bg-danger' : 'bg-secondary' }}">
                                        Due: {{ $assignment->due_date->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No Due Date</span>
                                @endif
                            </div>
                            <div>
                                @if($isSubmitted)
                                    @if($submission->status === 'graded')
                                        <span class="badge bg-primary"><i class="bi bi-award"></i> Graded</span>
                                    @else
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Submitted</span>
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                @endif
                            </div>
                        </div>

                        @if($assignment->file_path)
                            <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 mb-3">
                                <i class="bi bi-download"></i> Download Material
                            </a>
                        @endif

                        @if(!$isSubmitted)
                            <div class="d-flex gap-2">
                                <a href="{{ route('tenant.student.assignments.show', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" class="btn btn-outline-secondary btn-sm w-50">
                                    <i class="bi bi-eye"></i> Open
                                </a>
                                <button class="btn btn-primary btn-sm w-50" data-bs-toggle="modal" data-bs-target="#submitModal{{ $assignment->id }}">
                                    <i class="bi bi-upload"></i> Submit
                                </button>
                            </div>
                        @elseif($submission->status === 'graded')
                            <div class="d-flex gap-2">
                                <a href="{{ route('tenant.student.assignments.show', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" class="btn btn-outline-secondary btn-sm w-50">
                                    <i class="bi bi-eye"></i> Open
                                </a>
                                <button class="btn btn-primary btn-sm w-50" disabled>
                                    <i class="bi bi-award"></i> Graded
                                </button>
                            </div>
                        @else
                            <div class="d-flex gap-2">
                                <a href="{{ route('tenant.student.assignments.show', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" class="btn btn-outline-secondary btn-sm w-50">
                                    <i class="bi bi-eye"></i> Open
                                </a>
                                <button class="btn btn-success btn-sm w-50" disabled>
                                    <i class="bi bi-check-all"></i> Submitted
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submit Modal -->
            @if(!$isSubmitted)
            <div class="modal fade" id="submitModal{{ $assignment->id }}" tabindex="-1" aria-labelledby="submitModalLabel{{ $assignment->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="submitModalLabel{{ $assignment->id }}"><i class="bi bi-upload me-2"></i> Submit Assignment</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="fw-bold mb-3 text-secondary">{{ $assignment->title }}</h6>
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
                                <button type="submit" class="btn btn-primary w-100 fw-semibold">Submit Assignment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        @empty
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm text-center py-5">
                    <div class="fs-1 text-muted mb-3"><i class="bi bi-journal-x"></i></div>
                    <h5 class="text-muted">No assignments available at the moment.</h5>
                    <p class="mb-0 text-muted small">Check back later or contact your instructor.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $assignments->links() }}
    </div>
</div>
@endsection
