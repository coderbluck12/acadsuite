@extends('layouts.admin')

@section('sidebar-nav')
<nav>
    <a href="{{ route('tenant.student.dashboard', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.dashboard') ? 'active' : '' }} m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="{{ route('tenant.student.courses', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.courses') ? 'active' : '' }} m-2">
        <i class="bi bi-book me-2"></i> Courses
    </a>
    <a href="{{ route('tenant.student.assignments', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.assignments') ? 'active' : '' }} m-2">
        <i class="bi bi-file-earmark-text me-2"></i> Assignments
    </a>
    <a href="{{ route('tenant.student.notifications', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.notifications') ? 'active' : '' }} m-2">
        <i class="bi bi-bell me-2"></i> Notifications
    </a>
    <a href="{{ route('tenant.marketplace.index', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.marketplace*') ? 'active' : '' }} m-2">
        <i class="bi bi-shop me-2"></i> Store
    </a>
    <a href="{{ route('tenant.resources', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->is('resources*') ? 'active' : '' }} m-2">
        <i class="bi bi-folder2-open me-2"></i> Resources
    </a>
    <hr class="border-white opacity-25 mx-3">
    <a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="m-2" target="_blank">
        <i class="bi bi-box-arrow-up-right me-2"></i> Portal Home
    </a>
    <form method="POST" action="{{ route('tenant.logout', ['tenant' => $tenant->subdomain]) }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="mb-3">
        <a href="{{ route('tenant.student.courses', ['tenant' => $tenant->subdomain]) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Courses
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : asset('assets/publications.jpg') }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $course->title }}">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h2 class="card-title fw-bold text-primary">{{ $course->title }}</h2>
                    <div class="text-muted mb-3">
                        <i class="bi bi-bar-chart me-1"></i> {{ $course->level ?? 'All Levels' }} | 
                        <i class="bi bi-clock me-1"></i> {{ $course->duration ?? 'Self-paced' }}
                    </div>
                    
                    <h5 class="mt-4">Course Description</h5>
                    <p class="card-text text-muted" style="white-space: pre-wrap;">{{ $course->description }}</p>
                    
                    <div class="mt-4">
                        @if($isEnrolled)
                            <button class="btn btn-success" disabled>
                                <i class="bi bi-check-circle me-1"></i> You are enrolled in this course
                            </button>
                            
                            @if(isset($resources) && $resources->count() > 0)
                                <div class="mt-5">
                                    <h5 class="fw-bold mb-3 border-bottom pb-2">Course Resources</h5>
                                    <div class="row g-3">
                                        @foreach($resources as $resource)
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd !important;">
                                                <div class="card-body p-3">
                                                    <h6 class="fw-semibold text-primary mb-1">{{ $resource->title }}</h6>
                                                    <p class="card-text text-muted small mb-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $resource->description }}</p>
                                                    @if($resource->file_path)
                                                        <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" download class="btn btn-outline-success btn-sm rounded-pill px-3">
                                                            <i class="bi bi-download me-1"></i> Download
                                                        </a>
                                                    @elseif($resource->external_url || $resource->link)
                                                        <a href="{{ $resource->external_url ?? $resource->link }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                            <i class="bi bi-link-45deg me-1"></i> Visit Link
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        @else
                            <form action="{{ route('tenant.student.courses.enroll', ['tenant' => $tenant->subdomain, 'course' => $course->id]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Enroll Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
