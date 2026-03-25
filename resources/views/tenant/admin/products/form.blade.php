@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <h4 class="mb-4">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h4>
    <div class="card shadow-sm p-4">
        <form method="POST"
            action="{{ isset($product)
                ? route('tenant.admin.products.update', ['tenant' => $tenant->subdomain, 'product' => $product->id])
                : route('tenant.admin.products.store', ['tenant' => $tenant->subdomain]) }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $product->title ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price (₦) *</label>
                    <input type="number" name="price" step="0.01" min="0" class="form-control" value="{{ old('price', $product->price ?? '0.00') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description *</label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Format *</label>
                    <select name="format" id="format" class="form-select" required onchange="toggleSoftCopyField()">
                        <option value="hard_copy" {{ old('format', $product->format ?? 'hard_copy') == 'hard_copy' ? 'selected' : '' }}>Hard Copy (Physical Book)</option>
                        <option value="soft_copy" {{ old('format', $product->format ?? 'hard_copy') == 'soft_copy' ? 'selected' : '' }}>Soft Copy (Downloadable)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cover Image</label>
                    <input type="file" name="image_path" class="form-control" accept="image/*">
                    @if(isset($product) && $product->image_path)
                        <div class="mt-2 text-success small"><i class="bi bi-image"></i> Image uploaded</div>
                    @endif
                </div>
                <div class="col-md-4" id="softCopyDiv" style="display: {{ old('format', $product->format ?? 'hard_copy') == 'soft_copy' ? 'block' : 'none' }};">
                    <label class="form-label fw-semibold">Upload File (Soft Copy)</label>
                    <input type="file" name="file_path" id="file_path" class="form-control" accept=".pdf,.epub,.doc,.docx,.zip">
                    @if(isset($product) && $product->file_path)
                        <div class="mt-2 text-success small"><i class="bi bi-file-check-fill"></i> File currently uploaded</div>
                    @endif
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="isActive" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label ms-2 fw-semibold" for="isActive">Active (Visible in Marketplace)</label>
                </div>
            </div>

            <script>
                function toggleSoftCopyField() {
                    var format = document.getElementById('format').value;
                    var fileDiv = document.getElementById('softCopyDiv');
                    var fileInput = document.getElementById('file_path');
                    
                    if (format === 'soft_copy') {
                        fileDiv.style.display = 'block';
                        @if(!isset($product) || empty($product->file_path))
                        fileInput.required = true;
                        @endif
                    } else {
                        fileDiv.style.display = 'none';
                        fileInput.required = false;
                    }
                }
                
                // Initialize on load
                document.addEventListener('DOMContentLoaded', toggleSoftCopyField);
            </script>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">{{ isset($product) ? 'Update Product' : 'Add Product' }}</button>
                <a href="{{ route('tenant.admin.products.index', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
