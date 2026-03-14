@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    @php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->blogs()->count() >= 5;
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Blogs
            @if($isLimitReached)
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            @endif
        </h4>
        <button class="btn btn-primary rounded-pill px-4" {{ $isLimitReached ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#createBlogModal">
            <i class="bi bi-plus-circle me-1"></i> Add Blog
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="blogTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($blogs as $i => $blog)
                    <tr>
                        <td>{{ $blogs->firstItem() + $i }}</td>
                        <td>{{ Str::limit($blog->title, 60) }}</td>
                        <td><span class="badge {{ $blog->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $blog->is_published ? 'Published' : 'Draft' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editBlogModal{{ $blog->id }}"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('tenant.admin.blogs.destroy', ['tenant' => $tenant->subdomain, 'blog' => $blog]) }}" class="d-inline" onsubmit="return confirm('Delete this blog?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $blogs->links() }}
        </div>
    </div>

    @foreach($blogs as $blog)
    <!-- Edit Blog Modal -->
    <div class="modal fade" id="editBlogModal{{ $blog->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Blog Post</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="{{ route('tenant.admin.blogs.update', ['tenant' => $tenant->subdomain, 'blog' => $blog]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Blog Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Content</label>
                            <textarea name="content" class="form-control" rows="6" required>{{ $blog->content }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cover Image</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Visibility</label>
                            <select name="is_published" class="form-select w-100" required>
                                <option value="1" {{ $blog->is_published ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ !$blog->is_published ? 'selected' : '' }}>Draft</option>
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

<!-- Upload New Blog Modal -->
<div class="modal fade" id="createBlogModal" tabindex="-1" aria-labelledby="createBlogLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createBlogLabel">
                    <i class="bi bi-journal-plus me-2"></i> Create New Blog Post
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tenant.admin.blogs.store', ['tenant' => $tenant->subdomain]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="blogTitle" class="form-label fw-semibold">Blog Title</label>
                        <input type="text" name="title" class="form-control" id="blogTitle" placeholder="Enter a captivating title" required>
                    </div>
                    <div class="mb-3">
                        <label for="blogContent" class="form-label fw-semibold">Content</label>
                        <textarea name="content" id="blogContent" class="form-control" rows="6" placeholder="Write your blog content here..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="blogImage" class="form-label fw-semibold">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control" id="blogImage" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="visibility" class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" id="visibility" class="form-select w-100" required>
                            <option value="1">Publish Immediately</option>
                            <option value="0">Save as Draft</option>
                        </select>
                    </div>
                    <div class="d-grid" id="uploadBlog">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Save Blog
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function(){ 
    $('#blogTable').DataTable({ destroy: true, ordering: true }); 
    
    $('textarea[name="content"]').summernote({
        placeholder: 'Write your blog content here...',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
@endpush
