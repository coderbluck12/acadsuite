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
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Available Courses</h4>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <form action="{{ route('tenant.student.courses.join-private', ['tenant' => $tenant->subdomain]) }}" method="POST" class="d-flex justify-content-md-end">
                @csrf
                <div class="input-group" style="max-width: 350px;">
                    <input type="text" name="access_code" class="form-control" placeholder="Got a private access code?" required>
                    <button class="btn btn-outline-primary" type="submit">Join</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
            @php
                $isEnrolled = $course->students->isNotEmpty();
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card shadow border-0 h-100 p-0 m-0">
                    <img src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : asset('assets/publications.jpg') }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-primary">{{ $course->title }}</h5>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-bar-chart me-1"></i> {{ $course->level ?? 'All Levels' }} | 
                            <i class="bi bi-clock me-1"></i> {{ $course->duration ?? 'Self-paced' }}
                        </div>
                        <p class="card-text text-muted small mb-3 flex-grow-1">{{ Str::limit($course->description, 120) }}</p>

                        <div class="mt-auto">
                            @if($isEnrolled)
                                <button class="btn btn-outline-success w-100" disabled>
                                    <i class="bi bi-check-circle me-1"></i> Enrolled
                                </button>
                            @else
                                <form action="{{ route('tenant.student.courses.enroll', ['tenant' => $tenant->subdomain, 'course' => $course->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Enroll Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm text-center py-5">
                    <div class="fs-1 text-muted mb-3"><i class="bi bi-book"></i></div>
                    <h5 class="text-muted">No courses available at the moment.</h5>
                    <p class="mb-0 text-muted small">Check back later for new academic offerings.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $courses->links() }}
    </div>
</div>
@endsection
