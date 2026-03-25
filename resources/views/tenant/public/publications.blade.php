@extends('layouts.public')

@section('content')
    <section class="hero" style="position: relative; background: url('{{ asset('assets/publications.jpg') }}') center/cover no-repeat; height: 75vh; display: flex; align-items: center; justify-content: center; color: white;">

        <!-- navbar on hero -->
        <div class="position-absolute container top-0 start-50 translate-middle-x px-3 pt-4 w-100 "
            style="z-index: 10;">
            <nav class="navbar navbar-expand-md border border-white rounded-5 glass-navbar px-4 py-2 w-100" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}">
                    @if($tenant->avatar)
                        <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="Logo" style="width: 100px;">
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
                            <a href="/publications" class="nav-link active rounded-5" style="color: inherit; text-decoration: none; background-color: #dc3545; padding-inline: 20px;">Publications</a>
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
                            <a href="/marketplace" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Store</a>
                        </li>
                        @if($tenant->orcid_url)
                        <li class="nav-item">
                            <a href="{{ $tenant->orcid_url }}" target="_blank" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">ORCID</a>
                        </li>
                        @endif
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
        <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(253, 46, 46, 0.6);"></div>
        <div class="hero-content" style="position: relative; z-index: 2; text-align: center;">
            <h6 class="display-3 fw-bold ">Publications</h6>
            <p class="fs-4">Home &gt; Publications</p>
        </div>
    </section>

    <!-- Objectives of Publications -->
    <section class="container my-5 px-4">
        <h3 class="fw-semibold text-primary mb-3">What Our Publications Aim to Achieve</h3>
        <p class="text-muted fs-5">
            Our publications aim to advance knowledge in computer science and technology, promote innovative solutions
            to campus and societal challenges, and provide actionable insights for researchers, students, and educators.
            Each paper highlights practical applications, methodological rigor, and contributions to the field.
        </p>
    </section>

    <section>
        <div class="container my-5 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-semibold text-primary m-0">Published Publications</h3>
                <a href="{{ route('tenant.login', ['tenant' => $tenant->subdomain]) }}" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i> Login to Access More
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="row mb-4 gap-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search publications..." id="searchInput">
                </div>
            </div>

            <div class="row g-4" id="publicationsList">
                @forelse($publications ?? [] as $publication)
                <div class="col-12 publication-card" data-title="{{ strtolower($publication->title) }}">
                    <div class="card shadow-lg border-0 p-3">
                        <div class="card-body">
                            <h4 class="card-title fw-semibold text-primary mb-2">
                                {{ $publication->title }}
                            </h4>
                            <h6 class="text-muted mb-3">{{ $publication->authors ?? $tenant->owner_name }}</h6>
                            <div class="small text-secondary mb-3">
                                <i class="bi bi-calendar-check me-1"></i>
                                <strong>Published:</strong> {{ $publication->published_at ? $publication->published_at->format('Y') : $publication->created_at->format('Y') }}
                            </div>
                            <p class="card-text">
                                {{ Str::limit($publication->abstract ?? $publication->description, 200) }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <a href="{{ route('tenant.publications.show', ['tenant' => $tenant->subdomain, 'publication' => $publication->id]) }}" class="btn btn-primary w-100 btn-sm rounded-pill">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No publications found at this time.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-light pt-5 pb-4 mt-5">
        <div class="container px-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Have Questions?</h5>
                    <ul class="list-unstyled">
                        @if($tenant->address)<li class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ $tenant->address }}</li>@endif
                        @if($tenant->phone)<li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $tenant->phone }}</li>@endif
                        @if($tenant->email)<li><i class="bi bi-envelope me-2"></i>{{ $tenant->email }}</li>@endif
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="{{ route('tenant.publications', ['tenant' => $tenant->subdomain]) }}" class="text-light text-decoration-none">Publications</a></li>
                        <li><a href="{{ route('tenant.blogs', ['tenant' => $tenant->subdomain]) }}" class="text-light text-decoration-none">Blog</a></li>
                        <li><a href="{{ route('tenant.courses', ['tenant' => $tenant->subdomain]) }}" class="text-light text-decoration-none">Courses</a></li>
                        <li><a href="{{ route('tenant.resources', ['tenant' => $tenant->subdomain]) }}" class="text-light text-decoration-none">Resources</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    @if(is_array($tenant->social_links) && count($tenant->social_links))
                    <h5 class="fw-bold mb-3">Connect With Us</h5>
                    <div>
                        @if(!empty($tenant->social_links['twitter']))<a href="{{ $tenant->social_links['twitter'] }}" class="text-light me-3 fs-5" target="_blank"><i class="bi bi-twitter-x"></i></a>@endif
                        @if(!empty($tenant->social_links['facebook']))<a href="{{ $tenant->social_links['facebook'] }}" class="text-light me-3 fs-5" target="_blank"><i class="bi bi-facebook"></i></a>@endif
                        @if(!empty($tenant->social_links['instagram']))<a href="{{ $tenant->social_links['instagram'] }}" class="text-light fs-5" target="_blank"><i class="bi bi-instagram"></i></a>@endif
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-center mt-4 small">&copy; {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</div>
        </div>
    </footer>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("searchInput");
            const publications = document.querySelectorAll(".publication-card");

            if(searchInput) {
                searchInput.addEventListener("input", () => {
                    const searchText = searchInput.value.toLowerCase();
                    publications.forEach(card => {
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
