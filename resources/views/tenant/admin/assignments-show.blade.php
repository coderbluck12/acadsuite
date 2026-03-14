@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Review Submissions: {{ $assignment->title }}</h4>
        <a href="{{ route('tenant.admin.assignments.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="submissionTable" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Submission File</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Grading Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignment->submissions as $i => $submission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $submission->student->name }}</td>
                        <td>
                            @if($submission->file_path)
                                <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn btn-sm btn-outline-primary" download target="_blank">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @else
                                <span class="text-muted">No file</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->status === 'graded')
                                <span class="badge bg-success">Graded</span>
                            @else
                                <span class="badge bg-warning text-dark">Submitted</span>
                            @endif
                        </td>
                        <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            @if($submission->status === 'graded')
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-check-circle"></i> Graded
                                </button>
                            @else
                                <form action="{{ route('tenant.admin.assignments.grade', ['tenant' => $tenant->subdomain, 'assignment' => $assignment->id, 'submission' => $submission->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this submission as graded?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Mark as Graded
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#submissionTable').DataTable({ destroy: true, ordering: true });
    });
</script>
@endpush
