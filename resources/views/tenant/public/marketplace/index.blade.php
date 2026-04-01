@extends('layouts.public')

@section('content')
    <section class="hero" style="position: relative; background: url('{{ asset('assets/publications.jpg') }}') center/cover no-repeat; height: 50vh; display: flex; align-items: center; justify-content: center; color: white;">

        <!-- navbar on hero -->
        <div class="position-absolute container top-0 start-50 translate-middle-x px-3 pt-4 w-100 "
            style="z-index: 10;">
            <nav class="navbar navbar-expand-md border border-white rounded-5 glass-navbar px-4 py-2 w-100" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}">
                    @if($tenant->logo)
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" style="width: 100px;">
                    @else
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width: 100px;">
                    @endif
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Nav Items -->
                <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                    <ul class="navbar-nav gap-2" id="mainTab" role="tablist">
                        <li class="nav-item">
                            <a href="/" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="/publications" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Publications</a>
                        </li>
                        <li class="nav-item">
                            <a href="/resources" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Resources</a>
                        </li>
                        <li class="nav-item">
                            <a href="/blogs" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Blogs</a>
                        </li>
                        <li class="nav-item">
                            <a href="/courses" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Courses</a>
                        </li>
                        <li class="nav-item">
                            <a href="/marketplace" class="nav-link active rounded-5" style="color: inherit; text-decoration: none; background-color: #dc3545; padding-inline: 20px;">Store</a>
                        </li>
                        <li class="nav-item">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="/admin/profile" class="btn btn-primary rounded-pill px-3 py-1 ms-2">Dashboard</a>
                                @else
                                    <a href="/student/dashboard" class="btn btn-primary rounded-pill px-3 py-1 ms-2">Dashboard</a>
                                @endif
                            @else
                                <a href="/login" class="btn btn-primary rounded-pill px-3 py-1 ms-2">Login</a>
                            @endauth
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6);"></div>
        <div class="hero-content" style="position: relative; z-index: 2; text-align: center;">
            <h6 class="display-3 fw-bold ">Marketplace</h6>
            <p class="fs-4">Books & Resources</p>
        </div>
    </section>

    <section>
        <div class="container my-5 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-semibold text-primary m-0">Store Products</h3>
            </div>

            <!-- Search & Filter -->
            <div class="row mb-4 gap-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search products..." id="searchProductInput">
                </div>
            </div>

            <div class="row g-4" id="productsList">
                @forelse($products as $product)
                <div class="col-md-4 col-sm-6 product-card" data-title="{{ strtolower($product->title) }}">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/publications.jpg') }}" class="card-img-top" alt="{{ $product->title }}" style="height: 250px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold text-primary mb-2">{{ Str::limit($product->title, 50) }}</h5>
                            <div class="mb-3">
                                <span class="badge {{ $product->format === 'hard_copy' ? 'bg-secondary' : 'bg-info text-dark' }}">
                                    {{ $product->format === 'hard_copy' ? 'Hard Copy' : 'Soft Copy (PDF)' }}
                                </span>
                            </div>
                            <p class="card-text text-muted mb-4">{{ Str::limit($product->description, 100) }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                <h4 class="fw-bold mb-0 text-dark">₦{{ number_format($product->price, 2) }}</h4>
                                <a href="{{ route('tenant.marketplace.show', ['tenant' => $tenant->subdomain, 'product' => $product->id]) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No products available in the marketplace yet.</p>
                </div>
                @endforelse
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-light pt-5 pb-4 mt-5">
        <div class="container px-5">
            <div class="text-center mt-4 small">&copy; {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</div>
        </div>
    </footer>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("searchProductInput");
            const products = document.querySelectorAll(".product-card");

            if(searchInput) {
                searchInput.addEventListener("input", () => {
                    const searchText = searchInput.value.toLowerCase();
                    products.forEach(card => {
                        const title = card.getAttribute("data-title");
                        if(title.includes(searchText)) {
                            card.style.display = "block";
                        } else {
                            card.style.display = "none";
                        }
                    });
                });
            }
        });
    </script>
    @endpush
@endsection
