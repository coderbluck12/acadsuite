@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .hero { background: linear-gradient(135deg, #1a237e 0%, #3F51B5 60%, #283593 100%); min-height: 90vh; display: flex; align-items: center; }
        .glass-card { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); border-radius: 1rem; }
        .feature-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .btn-hero { padding: 0.8rem 2rem; font-weight: 600; font-size: 1rem; }
        .count-badge { background: rgba(255,255,255,0.2); color: white; border-radius: 50px; padding: 0.3rem 0.9rem; font-weight: 700; font-size: 1.1rem; }
    </style>
@endpush

@section('body')
{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark" style="background:rgba(26,35,126,0.95);backdrop-filter:blur(8px);position:fixed;top:0;width:100%;z-index:1000;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-5" href="{{ route('landing.home') }}">
            <img src="{{ asset('assets/logo.png') }}" height="40" class="me-2" alt="AcadSuite">
            AcadSuite
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
                <li class="nav-item"><a href="#how-it-works" class="nav-link">How It Works</a></li>
                <li class="nav-item"><a href="{{ route('landing.register') }}" class="btn btn-warning rounded-pill px-4 fw-semibold ms-2">Create Your Suite →</a></li>
            </ul>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section class="hero text-white pt-5">
    <div class="container py-5 mt-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="mb-3">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold">🎓 Powered by Pentagonware</span>
                </div>
                <h1 class="display-4 fw-bold mb-4" style="line-height:1.15;">Your Own Academic Portal <span style="color:#FFD700;">In Minutes</span></h1>
                <p class="lead opacity-90 mb-5">Create a fully branded subdomain for your research, publications, courses, and student management. Trusted by <strong class="count-badge">{{ $totalAcademics }}</strong> academics worldwide.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('landing.register') }}" class="btn btn-warning btn-hero rounded-pill">🚀 Create Your Suite — Free</a>
                    <a href="#features" class="btn btn-outline-light btn-hero rounded-pill">See Features</a>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-4 opacity-75 small">
                    <span>✅ Custom Subdomain</span>
                    <span>✅ Student Management</span>
                    <span>✅ Publications CMS</span>
                    <span>✅ No Coding Required</span>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="glass-card p-4">
                    <div class="text-start mb-3">
                        <span class="badge bg-success px-3 py-2 rounded-pill">● Live Preview</span>
                    </div>
                    <div class="rounded-3 overflow-hidden" style="background:rgba(255,255,255,0.1);padding:1.5rem;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rounded-circle bg-warning" style="width:10px;height:10px;"></div>
                            <small class="text-white-50">drsmith.acadsuite.local</small>
                        </div>
                        <h5 class="text-white fw-bold mb-1">Prof. David Smith</h5>
                        <p class="opacity-75 small mb-3">Researcher & Educator · University of Lagos</p>
                        <div class="row g-2 text-center">
                            <div class="col-4"><div class="glass-card p-2"><div class="fw-bold text-warning">24</div><small class="opacity-75">Papers</small></div></div>
                            <div class="col-4"><div class="glass-card p-2"><div class="fw-bold text-warning">8</div><small class="opacity-75">Courses</small></div></div>
                            <div class="col-4"><div class="glass-card p-2"><div class="fw-bold text-warning">150</div><small class="opacity-75">Students</small></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<section id="features" class="py-5" style="background:#f8f9fa;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Everything You Need</h2>
            <p class="text-muted lead">One platform for your entire academic digital presence.</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon' => 'bi-journal-text', 'color' => 'text-primary bg-primary', 'title' => 'Publications Manager', 'desc' => 'Showcase your research papers, journals, and academic work with a beautiful, searchable interface.'],
                ['icon' => 'bi-book', 'color' => 'text-success bg-success', 'title' => 'Course Management', 'desc' => 'Create and manage courses with descriptions, levels, and enrollment management.'],
                ['icon' => 'bi-people', 'color' => 'text-warning bg-warning', 'title' => 'Student Portal', 'desc' => 'Students register and get their own dashboard with assignments, notifications, and course access.'],
                ['icon' => 'bi-pencil-square', 'color' => 'text-danger bg-danger', 'title' => 'Blog Platform', 'desc' => 'Share your thoughts, research insights, and academic updates through a built-in blog.'],
                ['icon' => 'bi-folder2-open', 'color' => 'text-info bg-info', 'title' => 'Resource Library', 'desc' => 'Upload PDFs, links, and learning materials for students to access anytime.'],
                ['icon' => 'bi-globe', 'color' => 'text-secondary bg-secondary', 'title' => 'Custom Subdomain', 'desc' => 'Your own branded URL like yourname.acadsuite.com — no technical setup required.'],
            ] as $feature)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="feature-icon {{ $feature['color'] }} bg-opacity-10 mb-3">
                        <i class="bi {{ $feature['icon'] }} {{ $feature['color'] }}"></i>
                    </div>
                    <h5 class="fw-bold">{{ $feature['title'] }}</h5>
                    <p class="text-muted mb-0">{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- How It Works --}}
<section id="how-it-works" class="py-5">
    <div class="container py-4 text-center">
        <h2 class="fw-bold display-6 mb-2">How It Works</h2>
        <p class="text-muted lead mb-5">Go from zero to your own academic portal in 3 steps.</p>
        <div class="row g-4">
            @foreach([['1','Register & Choose Subdomain','Pick your unique subdomain (e.g., yourname.acadsuite.com) and fill your details.'],['2','Set Up Your Suite','Login to your admin panel, update your profile, and start adding publications, courses, and resources.'],['3','Share with the World','Your portal is live instantly. Share the link with students, colleagues, and the world.']] as [$step,$title,$desc])
            <div class="col-md-4">
                <div class="card border-0 bg-light h-100 p-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold" style="width:52px;height:52px;font-size:1.3rem;">{{ $step }}</div>
                    <h5 class="fw-bold">{{ $title }}</h5>
                    <p class="text-muted mb-0">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-5">
            <a href="{{ route('landing.register') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-semibold shadow">Create Your Suite Now — It's Free 🎓</a>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-dark text-light py-4">
    <div class="container text-center">
        <p class="mb-1 fw-semibold">AcadSuite — Powered by <strong>Pentagonware</strong></p>
        <small class="opacity-50">&copy; {{ date('Y') }} Pentagonware. All rights reserved.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
