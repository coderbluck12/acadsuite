@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-book me-2 text-success"></i>{{ $course->title }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('tenant.admin.courses.edit', ['tenant' => $tenant->subdomain, 'course' => $course]) }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('tenant.admin.courses.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Cover & Meta --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                @if($course->cover_image)
                <img src="{{ asset('storage/' . $course->cover_image) }}" class="card-img-top" alt="Cover" style="height:220px;object-fit:cover;">
                @else
                <div class="bg-gradient text-white d-flex align-items-center justify-content-center" style="height:220px;background:linear-gradient(135deg,#0d6efd,#6610f2);">
                    <i class="bi bi-book display-1 opacity-50"></i>
                </div>
                @endif
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="fw-bold fs-4 text-primary">{{ $course->students->count() }}</div>
                            <small class="text-muted">Students</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-4 text-warning">{{ $course->assignments->count() }}</div>
                            <small class="text-muted">Assignments</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-4 text-success">
                                <span class="badge {{ $course->is_published ? 'bg-success' : 'bg-secondary' }} fs-6">{{ $course->is_published ? 'Live' : 'Draft' }}</span>
                            </div>
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary mb-3">Course Details</h6>
                    <ul class="list-unstyled mb-0">
                        @if($course->level)
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-bar-chart me-2"></i>Level</span>
                            <span class="fw-semibold">{{ $course->level }}</span>
                        </li>
                        @endif
                        @if($course->duration)
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-clock me-2"></i>Duration</span>
                            <span class="fw-semibold">{{ $course->duration }}</span>
                        </li>
                        @endif
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-eye me-2"></i>Visibility</span>
                            <span class="badge bg-info text-dark">{{ ucfirst($course->visibility) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted"><i class="bi bi-calendar me-2"></i>Created</span>
                            <span class="fw-semibold small">{{ $course->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Description & Enrolled Students --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary mb-2"><i class="bi bi-info-circle me-1"></i>Description</h6>
                    <div class="text-muted" style="white-space: pre-wrap; line-height: 1.7;">{{ $course->description ?: 'No description provided.' }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people me-2 text-primary"></i>Enrolled Students ({{ $course->students->count() }})</span>
                </div>
                <div class="card-body p-0">
                    @forelse($course->students as $student)
                    <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:36px;height:36px;flex-shrink:0;">
                            <span class="fw-bold text-primary small">{{ strtoupper(substr($student->name,0,1)) }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $student->name }}</div>
                            <small class="text-muted">{{ $student->email }}</small>
                        </div>
                        <small class="text-muted">Enrolled {{ $student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y') : '—' }}</small>
                    </div>
                    @empty
                    <div class="text-muted text-center py-4">No students enrolled yet.</div>
                    @endforelse
                </div>
            </div>

            @if($course->assignments->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-file-earmark-text me-2 text-warning"></i>Related Assignments ({{ $course->assignments->count() }})</div>
                <div class="list-group list-group-flush">
                    @foreach($course->assignments as $assignment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $assignment->title }}</div>
                            <small class="text-muted">{{ $assignment->due_date?->format('Due: d M Y') ?? 'No due date' }}</small>
                        </div>
                        <span class="badge {{ $assignment->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $assignment->is_published ? 'Published' : 'Draft' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
