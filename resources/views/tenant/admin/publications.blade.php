@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    @php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->publications()->count() >= 5;
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Publications
            @if($isLimitReached)
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            @endif
        </h4>
        <button class="btn btn-primary rounded-pill px-4" {{ $isLimitReached ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-plus-circle me-1"></i> Add Publication
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="pubTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Authors</th><th>Journal</th><th>Year</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($publications as $i => $pub)
                    <tr>
                        <td>{{ $publications->firstItem() + $i }}</td>
                        <td>{{ Str::limit($pub->title, 60) }}</td>
                        <td class="small">{{ Str::limit($pub->authors, 40) }}</td>
                        <td class="small">{{ $pub->journal ?: '—' }}</td>
                        <td>{{ $pub->year ?: '—' }}</td>
                        <td><span class="badge {{ $pub->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $pub->is_published ? 'Published' : 'Draft' }}</span></td>
                        <td>
                            <a href="{{ route('tenant.publications.show', ['tenant' => $tenant->subdomain, 'publication' => $pub->id]) }}" class="btn btn-sm btn-outline-info me-1" target="_blank" title="View Details"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editPubModal{{ $pub->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('tenant.admin.publications.destroy', ['tenant' => $tenant->subdomain, 'publication' => $pub]) }}" class="d-inline" onsubmit="return confirm('Delete this publication?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $publications->links() }}
        </div>
    </div>

    @foreach($publications as $pub)
    <!-- Edit Publication Modal -->
    <div class="modal fade" id="editPubModal{{ $pub->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Publication</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="{{ route('tenant.admin.publications.update', ['tenant' => $tenant->subdomain, 'publication' => $pub]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Publication Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $pub->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Author(s)</label>
                            <input type="text" name="authors" class="form-control" value="{{ $pub->authors }}" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-8">
                                <label class="form-label fw-semibold">Journal/Conference</label>
                                <input type="text" name="journal" class="form-control" value="{{ $pub->journal }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold">Year</label>
                                <input type="number" name="year" class="form-control" value="{{ $pub->year }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link (URL)</label>
                            <input type="url" name="link" class="form-control" value="{{ $pub->url }}">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Access Type *</label>
                                <select name="access_type" class="form-select" required onchange="toggleFileReq(this, 'editInput{{ $pub->id }}')">
                                    <option value="view" {{ ($pub->access_type ?? 'view') == 'view' ? 'selected' : '' }}>View Only</option>
                                    <option value="download" {{ ($pub->access_type ?? 'view') == 'download' ? 'selected' : '' }}>Downloadable</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Upload File</label>
                                <input type="file" name="file_path" id="editInput{{ $pub->id }}" class="form-control" accept=".pdf,.doc,.docx">
                                @if($pub->file_path) <small class="text-success"><i class="bi bi-check-circle"></i> File uploaded</small> @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Visibility</label>
                            <select name="is_published" class="form-select w-100" required>
                                <option value="1" {{ $pub->is_published ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ !$pub->is_published ? 'selected' : '' }}>Draft</option>
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

<!-- Upload Publication Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel"><i class="bi bi-journal-plus me-2"></i> Upload New Publication</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tenant.admin.publications.store', ['tenant' => $tenant->subdomain]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="pubTitle" class="form-label fw-semibold">Publication Title</label>
                        <input type="text" name="title" class="form-control" id="pubTitle" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label for="pubAuthor" class="form-label fw-semibold">Author(s)</label>
                        <input type="text" name="authors" class="form-control" id="pubAuthor" placeholder="Enter author name" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label class="form-label fw-semibold">Journal/Conference</label>
                            <input type="text" name="journal" class="form-control" placeholder="Optional">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control" placeholder="YYYY">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link (URL)</label>
                        <input type="url" name="link" class="form-control" placeholder="https://...">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Access Type *</label>
                            <select name="access_type" class="form-select" required onchange="toggleFileReq(this, 'createFileInput')">
                                <option value="view" selected>View Only</option>
                                <option value="download">Downloadable</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="createFileInput" class="form-label fw-semibold">Upload File</label>
                            <input type="file" name="file_path" id="createFileInput" class="form-control" accept=".pdf,.doc,.docx">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="visibility" class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" id="visibility" class="form-select w-100" required>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="publicationBtn"><i class="bi bi-upload"></i> Save Publication</button>
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
<script>
    $(document).ready(function(){ $('#pubTable').DataTable({ destroy: true, ordering: true }); });
    function toggleFileReq(select, inputId) {
        document.getElementById(inputId).required = (select.value === 'download');
    }
</script>
@endpush
