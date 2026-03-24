@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <h4 class="mb-4">{{ isset($publication) ? 'Edit Publication' : 'Add Publication' }}</h4>
    <div class="card shadow-sm p-4">
        <form method="POST"
            action="{{ isset($publication)
                ? route('tenant.admin.publications.update', ['tenant' => $tenant->subdomain, 'publication' => $publication])
                : route('tenant.admin.publications.store', ['tenant' => $tenant->subdomain]) }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($publication)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $publication->title ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Authors *</label>
                <input type="text" name="authors" class="form-control" value="{{ old('authors', $publication->authors ?? '') }}" required placeholder="e.g. Smith, J., Doe, A.">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Journal / Conference</label>
                    <input type="text" name="journal" class="form-control" value="{{ old('journal', $publication->journal ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Year</label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $publication->year ?? '') }}" min="1900" max="{{ date('Y') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_published" class="form-select">
                        <option value="1" {{ old('is_published', $publication->is_published ?? 1) == 1 ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ old('is_published', $publication->is_published ?? 1) == 0 ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Abstract</label>
                <textarea name="abstract" class="form-control" rows="4">{{ old('abstract', $publication->abstract ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">DOI / URL</label>
                <input type="url" name="url" class="form-control" value="{{ old('url', $publication->url ?? '') }}" placeholder="https://doi.org/...">
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                    @if(!empty($publication->cover_image))
                        <div class="mt-2"><img src="{{ asset('storage/' . $publication->cover_image) }}" height="80" class="rounded border"></div>
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Access Type *</label>
                    <select name="access_type" class="form-select" required onchange="toggleFileRequirement(this)">
                        <option value="view" {{ old('access_type', $publication->access_type ?? 'view') == 'view' ? 'selected' : '' }}>View Only</option>
                        <option value="download" {{ old('access_type', $publication->access_type ?? 'view') == 'download' ? 'selected' : '' }}>Downloadable</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">File Document</label>
                    <input type="file" name="file_path" id="file_path_input" class="form-control" accept=".pdf,.doc,.docx">
                    @if(!empty($publication->file_path))
                        <div class="mt-2 text-success small"><i class="bi bi-file-earmark-check"></i> File currently uploaded</div>
                    @endif
                </div>
            </div>
            
            <script>
                function toggleFileRequirement(select) {
                    const fileInput = document.getElementById('file_path_input');
                    if(select.value === 'download') {
                        @if(empty($publication->file_path))
                            fileInput.required = true;
                        @endif
                    } else {
                        fileInput.required = false;
                    }
                }
                // Run on load
                document.addEventListener('DOMContentLoaded', () => toggleFileRequirement(document.querySelector('select[name="access_type"]')));
            </script>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">{{ isset($publication) ? 'Update' : 'Add Publication' }}</button>
                <a href="{{ route('tenant.admin.publications.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
