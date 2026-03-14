@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    @php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->assignments()->count() >= 5;
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Assignments
            @if($isLimitReached)
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            @endif
        </h4>
        <button class="btn btn-primary rounded-pill px-4" {{ $isLimitReached ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
            <i class="bi bi-plus-circle me-1"></i> Create Assignment
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="assignmentTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Course</th><th>Due Date</th><th>Submissions</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($assignments as $i => $assignment)
                    <tr>
                        <td>{{ $assignments->firstItem() + $i }}</td>
                        <td>{{ $assignment->title }}</td>
                        <td>{{ $assignment->course?->title ?? '—' }}</td>
                        <td>{{ $assignment->due_date?->format('d M Y') ?? '—' }}</td>
                        <td><span class="badge bg-info text-dark">{{ $assignment->submissions->count() }}</span></td>
                        <td>
                            <a href="{{ route('tenant.admin.assignments.show', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" class="btn btn-sm btn-outline-success me-1" title="View Submissions"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editAssignmentModal{{ $assignment->id }}"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('tenant.admin.assignments.destroy', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $assignments->links() }}
        </div>
    </div>

    @foreach($assignments as $assignment)
    <!-- Edit Assignment Modal -->
    <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Assignment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="{{ route('tenant.admin.assignments.update', ['tenant' => $tenant->subdomain, 'assignment' => $assignment]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Assignment Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $assignment->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description / Instructions</label>
                            <textarea class="form-control" name="description" rows="3">{{ $assignment->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Related Course</label>
                            <select name="course_id" class="form-select">
                                <option value="">-- No Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $assignment->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $assignment->due_date?->format('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Attach File (Optional)</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Create Assignment Modal -->
<div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-labelledby="createAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createAssignmentModalLabel"><i class="bi bi-file-earmark-text me-2"></i> Create Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tenant.admin.assignments.store', ['tenant' => $tenant->subdomain]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Assignment Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Instructions</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Provide instructions..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Related Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">-- No Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Attach File (Optional)</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload"></i> Create Assignment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>$(document).ready(function(){ $('#assignmentTable').DataTable({ destroy: true, ordering: true }); });</script>
@endpush
