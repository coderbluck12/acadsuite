@extends('layouts.public')

@section('content')
    <section class="hero" style="position: relative; background: url('{{ asset('assets/publications.jpg') }}') center/cover no-repeat; height: 75vh; display: flex; align-items: center; justify-content: center; color: white;">
        <!-- Navbar -->
        <div class="position-absolute container top-0 start-50 translate-middle-x px-3 pt-4 w-100 "
            style="z-index: 10;">
            <nav class="navbar navbar-expand-md border border-white rounded-5 glass-navbar px-4 py-2 w-100" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                <a class="navbar-brand" href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}">
                    @if($tenant->avatar)
                        <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="Logo" style="width: 100px;">
                    @else
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width: 100px;">
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                    <ul class="navbar-nav gap-2" id="mainTab" role="tablist">
                        <li class="nav-item"><a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Home</a></li>
                        <li class="nav-item"><a href="{{ route('tenant.publications', ['tenant' => $tenant->subdomain]) }}" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Publications</a></li>
                        <li class="nav-item"><a href="{{ route('tenant.resources', ['tenant' => $tenant->subdomain]) }}" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Resources</a></li>
                        <li class="nav-item"><a href="{{ route('tenant.blogs', ['tenant' => $tenant->subdomain]) }}" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Blogs</a></li>
                        <li class="nav-item"><a href="{{ route('tenant.courses', ['tenant' => $tenant->subdomain]) }}" class="nav-link active rounded-5" style="color: inherit; text-decoration: none; background-color: #dc3545; padding-inline: 20px;">Courses</a></li>
                        @if($tenant->orcid_url)
                        <li class="nav-item"><a href="{{ $tenant->orcid_url }}" target="_blank" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">ORCID</a></li>
                        @endif
                        <li class="nav-item"><a href="{{ route('tenant.login', ['tenant' => $tenant->subdomain]) }}" class="btn btn-primary rounded-pill px-3 py-1 ms-2">Login</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(253, 46, 46, 0.6);"></div>
        <div class="hero-content" style="position: relative; z-index: 2; text-align: center;">
            <h6 class="display-3 fw-bold ">Courses</h6>
            <p class="fs-4">Home &gt; Courses</p>
        </div>
    </section>

    <!-- Intro Section -->
    <section class="container py-5 px-4 px-md-4">
        <h3 class="fw-semibold text-primary mb-4">Helpful Educational Courses</h3>
        <p class="text-muted fs-5 mb-0">
            Our courses are designed to empower learners with practical skills and in-depth knowledge...
        </p>
    </section>

    <!-- Filter Dropdown -->
    <section class="container mb-4">
        <div class="row">
            <div class="col-md-4 ">
                <select class="form-select" id="accessFilter">
                    <option value="">Filter by Access</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section class="container mt-4 mb-5">
        <div class="row g-4" id="coursesContainer">

            @forelse($courses ?? [] as $course)
            <div class="col-12 course-card" data-access="{{ strtolower($course->access_type ?? 'public') }}"
                onclick="window.location.href='{{ route('tenant.courses.show', ['tenant' => $tenant->subdomain, 'course' => $course->id]) }}'" style="cursor: pointer;">
                <div class="card shadow-lg border-0 p-3 h-100" style="transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body">
                        <h4 class="card-title fw-semibold text-primary mb-2">
                            {{ $course->title }}
                        </h4>
                        <h6 class="text-muted mb-3">{{ $tenant->owner_name }}</h6>
                        <div class="small text-secondary mb-3"><i class="bi bi-calendar-check me-1"></i><strong>Year:</strong> {{ $course->created_at->format('Y') }}</div>
                        <p class="card-text">{{ Str::limit($course->description, 150) }}</p>
                        
                        @if(strtolower($course->access_type ?? 'public') == 'public')
                            <span class="badge bg-success">Public</span>
                        @else
                            <span class="badge bg-danger">Private</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">No courses are available right now.</p>
            </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $courses->links('pagination::bootstrap-5') ?? '' }}
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
            const accessFilter = document.getElementById("accessFilter");
            const courseCards = document.querySelectorAll(".course-card");

            if(accessFilter) {
                accessFilter.addEventListener("change", () => {
                    const selectedAccess = accessFilter.value.toLowerCase();
                    courseCards.forEach(card => {
                        const cardAccess = card.getAttribute("data-access");
                        if (selectedAccess === "" || selectedAccess === cardAccess) {
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
