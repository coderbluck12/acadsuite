@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    @php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->resources()->count() >= 5;
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Resources
            @if($isLimitReached)
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            @endif
        </h4>
        <button class="btn btn-primary rounded-pill px-4" {{ $isLimitReached ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-plus-circle me-1"></i> Add Resource
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="resTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Type</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($resources as $i => $res)
                    <tr>
                        <td>{{ $resources->firstItem() + $i }}</td>
                        <td>{{ Str::limit($res->title, 60) }}</td>
                        <td>{{ strtoupper($res->file_type) }}</td>
                        <td><span class="badge {{ $res->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $res->is_published ? 'Published' : 'Draft' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editResModal{{ $res->id }}"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('tenant.admin.resources.destroy', ['tenant' => $tenant->subdomain, 'resource' => $res]) }}" class="d-inline" onsubmit="return confirm('Delete this resource?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $resources->links() }}
        </div>
    </div>

    @foreach($resources as $res)
    <!-- Edit Resource Modal -->
    <div class="modal fade" id="editResModal{{ $res->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="{{ route('tenant.admin.resources.update', ['tenant' => $tenant->subdomain, 'resource' => $res]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Resource Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $res->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description">{{ $res->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Related Course</label>
                            <select name="course_id" class="form-select">
                                <option value="">-- No Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $res->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_general" value="1" {{ $res->is_general ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">General Resource (Visible to all students)</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Visibility</label>
                            <select name="is_published" class="form-select w-100" required>
                                <option value="1" {{ $res->is_published ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ !$res->is_published ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Upload Resource Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel"><i class="bi bi-folder-plus me-2"></i> Upload New Resource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tenant.admin.resources.store', ['tenant' => $tenant->subdomain]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="pubTitle" class="form-label fw-semibold">Resource Title</label>
                        <input type="text" name="title" class="form-control" id="pubTitle" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label for="pubDesc" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" id="pubDesc" placeholder="Optional description..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pubFile" class="form-label fw-semibold">Upload File</label>
                        <input type="file" name="file" class="form-control" id="pubFile" required>
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
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_general" value="1">
                        <label class="form-check-label fw-semibold">General Resource (Visible to all students)</label>
                    </div>
                    <div class="mb-3">
                        <label for="visibility" class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" id="visibility" class="form-select w-100" required>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload p-1"></i> Upload</button>
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
<script>$(document).ready(function(){ $('#resTable').DataTable({ destroy: true, ordering: true }); });</script>
@endpush
