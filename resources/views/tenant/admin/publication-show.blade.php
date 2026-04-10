@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-journal-text me-2 text-info"></i>Publication Details</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editPubModal">
                <i class="bi bi-pencil me-1"></i> Edit
            </button>
            <a href="{{ route('tenant.admin.publications.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Cover Image --}}
        <div class="col-lg-4">
            @if($publication->cover_image)
            <img src="{{ asset('storage/' . $publication->cover_image) }}" alt="Cover" class="img-fluid rounded shadow mb-3" style="width:100%;height:280px;object-fit:cover;">
            @else
            <div class="rounded shadow mb-3 d-flex align-items-center justify-content-center bg-light" style="height:280px;">
                <i class="bi bi-journal-text display-1 text-muted opacity-50"></i>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary mb-3">Details</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-calendar me-2"></i>Year</span>
                            <span class="fw-semibold">{{ $publication->year ?: '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-journal me-2"></i>Journal</span>
                            <span class="fw-semibold small">{{ $publication->journal ?: '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-unlock me-2"></i>Access</span>
                            <span class="badge bg-{{ $publication->access_type === 'download' ? 'success' : 'primary' }}">{{ ucfirst($publication->access_type ?? 'view') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted"><i class="bi bi-eye me-2"></i>Status</span>
                            <span class="badge {{ $publication->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $publication->is_published ? 'Published' : 'Draft' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if($publication->file_path)
            <div class="mt-3">
                <a href="{{ asset('storage/' . $publication->file_path) }}" target="_blank" download class="btn btn-success w-100 rounded-pill">
                    <i class="bi bi-download me-2"></i> Download File
                </a>
            </div>
            @endif

            @if($publication->url)
            <div class="mt-2">
                <a href="{{ $publication->url }}" target="_blank" class="btn btn-outline-primary w-100 rounded-pill">
                    <i class="bi bi-link-45deg me-2"></i> View External Link
                </a>
            </div>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-primary mb-2">{{ $publication->title }}</h3>
                    <p class="text-muted mb-4"><i class="bi bi-people me-1"></i>{{ $publication->authors }}</p>

                    @if($publication->abstract)
                    <h6 class="fw-semibold text-secondary mb-2">Abstract</h6>
                    <div class="p-3 bg-light rounded" style="white-space: pre-wrap; line-height: 1.8;">{{ $publication->abstract }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editPubModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Publication</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{ route('tenant.admin.publications.update', ['tenant' => $tenant->subdomain, 'publication' => $publication]) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Publication Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $publication->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Author(s)</label>
                        <input type="text" name="authors" class="form-control" value="{{ $publication->authors }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label class="form-label fw-semibold">Journal/Conference</label>
                            <input type="text" name="journal" class="form-control" value="{{ $publication->journal }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control" value="{{ $publication->year }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Abstract</label>
                        <textarea name="abstract" class="form-control" rows="3">{{ $publication->abstract }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link (URL)</label>
                        <input type="url" name="url" class="form-control" value="{{ $publication->url }}">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Access Type *</label>
                            <select name="access_type" class="form-select" required>
                                <option value="view" {{ ($publication->access_type ?? 'view') == 'view' ? 'selected' : '' }}>View Only</option>
                                <option value="download" {{ ($publication->access_type ?? 'view') == 'download' ? 'selected' : '' }}>Downloadable</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Upload File</label>
                            <input type="file" name="file_path" class="form-control" accept=".pdf,.doc,.docx">
                            @if($publication->file_path) <small class="text-success"><i class="bi bi-check-circle"></i> File uploaded</small> @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" class="form-select w-100" required>
                            <option value="1" {{ $publication->is_published ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ !$publication->is_published ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
