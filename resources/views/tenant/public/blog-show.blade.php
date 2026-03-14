@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <section class="hero" style="position: relative; background: url('{{ asset('assets/publications.jpg') }}') center/cover no-repeat; height: 60vh; display: flex; align-items: center; justify-content: center; color: white;">
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
                        <li class="nav-item"><a href="{{ route('tenant.blogs', ['tenant' => $tenant->subdomain]) }}" class="nav-link active rounded-5" style="color: inherit; text-decoration: none; background-color: #dc3545; padding-inline: 20px;">Blogs</a></li>
                        <li class="nav-item"><a href="{{ route('tenant.courses', ['tenant' => $tenant->subdomain]) }}" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Courses</a></li>
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
            <h6 class="display-4 fw-bold" style="max-width: 800px; margin: 0 auto;">{{ $blog->title }}</h6>
            <p class="fs-4 mt-3">Home &gt; Blogs &gt; Post</p>
        </div>
    </section>

    <section class="container my-5">
        <div class="row">
            <!-- Blog Content -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <h1 class="fw-bold mb-3">{{ $blog->title }}</h1>
                <p class="text-muted">By <span class="fw-semibold text-primary">{{ $tenant->owner_name }}</span> | {{ $blog->created_at->format('M d, Y') }}</p>
                
                @if($blog->cover_image)
                    <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="{{ $blog->title }}" class="img-fluid rounded shadow-sm mb-4 w-100" style="max-height: 450px; object-fit: cover;">
                @else
                    <img src="{{ asset('assets/studious_gril.jpg') }}" alt="Blog Image" class="img-fluid rounded shadow-sm mb-4 w-100" style="max-height: 450px; object-fit: cover;">
                @endif
                
                <div class="blog-content fs-5" style="line-height: 1.8;">
                    {!! nl2br(e($blog->content)) !!}
                </div>
                
                <div class="mt-5 border-top pt-4">
                    <a href="{{ route('tenant.blogs', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to All Blogs
                    </a>
                </div>
            </div>

            <!-- Related Posts -->
            <div class="col-lg-4">
                <div class="bg-light p-4 rounded shadow-sm">
                    <h5 class="mb-4 fw-bold border-bottom pb-2 border-danger">Related Posts</h5>
                    <div class="list-group list-group-flush bg-transparent">
                        @forelse($related ?? [] as $relatedPost)
                            <a href="{{ route('tenant.blogs.show', ['tenant' => $tenant->subdomain, 'blog' => $relatedPost->id]) }}" class="list-group-item list-group-item-action bg-transparent border-0 px-0 pb-3 mb-3 border-bottom">
                                <h6 class="fw-bold mb-1 text-primary">{{ $relatedPost->title }}</h6>
                                <small class="text-muted">{{ $relatedPost->created_at->format('M d, Y') }}</small>
                            </a>
                        @empty
                            <p class="text-muted small">No related posts found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-light pt-5 pb-4 mt-5">
        <div class="container px-5 text-center small">
            &copy; {{ date('Y') }} {{ $tenant->name }}. All rights reserved.
        </div>
    </footer>
@endsection
