@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Marketplace Products</h4>
        <a href="{{ route('tenant.admin.products.create', ['tenant' => $tenant->subdomain]) }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> Add Product
        </a>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="productsTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Format</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $i => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $i }}</td>
                        <td>
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/publications.jpg') }}" height="40" width="40" style="object-fit: cover;" class="rounded border">
                        </td>
                        <td>{{ Str::limit($product->title, 50) }}</td>
                        <td>₦{{ number_format($product->price, 2) }}</td>
                        <td>
                            @if($product->format === 'hard_copy')
                                <span class="badge bg-secondary">Hard Copy</span>
                            @else
                                <span class="badge bg-info text-dark">Soft Copy</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tenant.admin.products.edit', ['tenant' => $tenant->subdomain, 'product' => $product->id]) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tenant.admin.products.destroy', ['tenant' => $tenant->subdomain, 'product' => $product->id]) }}" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $products->links() }}
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
    $(document).ready(function(){ 
        $('#productsTable').DataTable({ destroy: true, ordering: true }); 
    });
</script>
@endpush
