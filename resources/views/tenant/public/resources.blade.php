@extends('layouts.public')

@section('content')
    <section class="hero" style="position: relative; background: url('{{ asset('assets/publications.jpg') }}') center/cover no-repeat; height: 75vh; display: flex; align-items: center; justify-content: center; color: white;">

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
                            <a href="/resources" class="nav-link active rounded-5" style="color: inherit; text-decoration: none; background-color: #dc3545; padding-inline: 20px;">Resources</a>
                        </li>
                        <li class="nav-item">
                            <a href="/blogs" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Blogs</a>
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
                                    <a href="/admin/dashboard" class="btn btn-primary rounded-pill px-3 py-1 ms-2">Dashboard</a>
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
            <h6 class="display-3 fw-bold ">Resources</h6>
            <p class="fs-4">Home &gt; Resources</p>
        </div>
    </section>

    <section class="container my-5 px-4">
        <h3 class="fw-semibold text-primary mb-3">Resource Objectives</h3>
        <p class="text-muted fs-5">
            The purpose of our resources is to strengthen students’ understanding of core concepts in computer science
            and related disciplines. Each task encourages critical thinking, practical problem-solving, and the
            application of theoretical knowledge to real-world scenarios, helping learners develop the skills needed for
            academic and professional success.
        </p>
    </section>

    <section>
        <div class="container my-5 px-4">
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Top Header with Submission Button -->
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h3 class="fw-semibold text-primary m-0">Available Resources</h3>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#requestResourceModal">
                    <i class="bi bi-send me-1"></i> Request Resource
                </button>
            </div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs border-bottom-0" id="resourceTabs" role="tablist">
                @php
                    $categories = $resources->pluck('category')->unique()->filter()->values();
                    if($categories->isEmpty()) {
                        $categories = collect(['General']);
                    }
                @endphp
                
                @foreach($categories as $index => $category)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="{{ Str::slug($category) }}-tab" data-bs-toggle="tab"
                        data-bs-target="#{{ Str::slug($category) }}" type="button" role="tab" style="color: black; border: none; font-size: 18px; padding: 10px 20px; border-radius: 10px 10px 0 0;">
                        {{ $category }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-0 border rounded-bottom rounded-end p-4 bg-white shadow-sm" style="border-top-left-radius: 0 !important; min-height: 300px;">
                @foreach($categories as $index => $category)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ Str::slug($category) }}" role="tabpanel">
                    <div class="row g-4">
                        @php
                            $categoryResources = $category === 'General' && $resources->isEmpty() 
                                ? collect([]) 
                                : $resources->where('category', $category);
                        @endphp
                        
                        @forelse($categoryResources as $resource)
                        <div class="col-12 col-md-6 mb-3">
                            <div class="card shadow-sm border-0 h-100" style="transition: transform 0.2s; border-left: 4px solid #dc3545 !important;">
                                <div class="card-body">
                                    <h5 class="card-title fw-semibold text-primary mb-2">
                                        {{ $resource->title }}
                                    </h5>
                                    @if($resource->course)
                                        <strong class="small text-secondary mb-2 d-block">Course: {{ $resource->course->title }}</strong>
                                    @endif
                                    <div class="small text-muted mb-3">
                                        <i class="bi bi-clock"></i> <strong>Posted:</strong> {{ $resource->created_at->format('F d, Y, g:ia') }}
                                    </div>
                                    <p class="card-text text-muted mb-4">
                                        {{ Str::limit($resource->description, 180) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 text-end">
                                    @if($resource->file_path)
                                    <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                    @elseif($resource->link)
                                    <a href="{{ $resource->link }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-link-45deg me-1"></i> Visit Link
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-folder2-open display-4 text-muted mb-3 d-block"></i>
                            <p class="text-muted fs-5">No resources available in this category yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $resources->links('pagination::bootstrap-5') ?? '' }}
            </div>
        </div>
    </section>

    <!-- Request Resource Modal -->
    <div class="modal fade" id="requestResourceModal" tabindex="-1" aria-labelledby="requestResourceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="requestResourceModalLabel">Request a Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tenant.resource.request', ['tenant' => $tenant->subdomain]) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">Are you looking for a specific resource? Let us know what you need.</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-12">
                                <label for="phone" class="form-label">Phone Number (Optional)</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Resource Details *</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Please describe the resource you are looking for..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    @push('styles')
    <style>
        .nav-tabs .nav-link {
            border: none;
            color: black !important;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 10px 10px 0 0;
            background: #f8f9fa;
            margin-right: 5px;
        }

        .nav-tabs .nav-link.active {
            background: #dc3545;
            color: white !important;
            font-weight: bold;
            border-bottom: 3px solid #dc3545;
        }

        .tab-content {
            background: white;
            color: black;
            border-radius: 0 0 10px 10px;
            padding: 20px;
            margin-top: -1px;
            border-top: 3px solid #dc3545;
        }
    </style>
    @endpush
@endsection
