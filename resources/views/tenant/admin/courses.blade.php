@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    @php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->courses()->count() >= 5;
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Courses 
            @if($isLimitReached)
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            @endif
        </h4>
        <button class="btn btn-primary rounded-pill px-4" {{ $isLimitReached ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#createCourseModal">
            <i class="bi bi-plus-circle me-1"></i> Add Course
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="courseTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Level</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($courses as $i => $course)
                    <tr>
                        <td>{{ $courses->firstItem() + $i }}</td>
                        <td>{{ Str::limit($course->title, 60) }}</td>
                        <td>{{ $course->level ?: '—' }}</td>
                        <td><span class="badge {{ $course->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $course->is_published ? 'Published' : 'Draft' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editCourseModal{{ $course->id }}"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('tenant.admin.courses.destroy', ['tenant' => $tenant->subdomain, 'course' => $course]) }}" class="d-inline" onsubmit="return confirm('Delete this course?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $courses->links() }}
        </div>
    </div>

    @foreach($courses as $course)
    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal{{ $course->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="{{ route('tenant.admin.courses.update', ['tenant' => $tenant->subdomain, 'course' => $course]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $course->description }}</textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Level</label>
                                <input type="text" name="level" class="form-control" value="{{ $course->level }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Duration</label>
                                <input type="text" name="duration" class="form-control" value="{{ $course->duration }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cover Image</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Publish Status</label>
                            <select name="is_published" class="form-select w-100" required>
                                <option value="1" {{ $course->is_published ? 'selected' : '' }}>Published (Active)</option>
                                <option value="0" {{ !$course->is_published ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course Visibility</label>
                            <select name="visibility" class="form-select w-100" required>
                                <option value="general" {{ $course->visibility == 'general' ? 'selected' : '' }}>General (Public)</option>
                                <option value="private" {{ $course->visibility == 'private' ? 'selected' : '' }}>Private (Requires Code)</option>
                                <option value="hidden" {{ $course->visibility == 'hidden' ? 'selected' : '' }}>Hidden (Admin Only)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Access Code (If Private)</label>
                            <input type="text" name="access_code" class="form-control" value="{{ $course->access_code }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Create Course Modal -->
<div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createCourseModalLabel"><i class="bi bi-book me-2"></i> Create New Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tenant.admin.courses.store', ['tenant' => $tenant->subdomain]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="courseTitle" class="form-label fw-semibold">Course Title</label>
                        <input type="text" name="title" class="form-control" id="courseTitle" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label for="courseDesc" class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" id="courseDesc" placeholder="Description" rows="3"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="courseLevel" class="form-label fw-semibold">Level</label>
                            <input type="text" name="level" class="form-control" id="courseLevel" placeholder="e.g. Beginner">
                        </div>
                        <div class="col-6">
                            <label for="courseDuration" class="form-label fw-semibold">Duration</label>
                            <input type="text" name="duration" class="form-control" id="courseDuration" placeholder="e.g. 4 Weeks">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="courseMaterials" class="form-label fw-semibold">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control" id="courseMaterials" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="is_published" class="form-label fw-semibold">Publish Status</label>
                        <select name="is_published" id="is_published" class="form-select w-100" required>
                            <option value="1">Published (Active)</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Course Visibility</label>
                        <select name="visibility" class="form-select w-100" required>
                            <option value="general">General (Public)</option>
                            <option value="private">Private (Requires Code)</option>
                            <option value="hidden">Hidden (Admin Only)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Access Code (If Private)</label>
                        <input type="text" name="access_code" class="form-control" placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload"></i> Create Course</button>
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
<script>$(document).ready(function(){ $('#courseTable').DataTable({ destroy: true, ordering: true }); });</script>
@endpush
