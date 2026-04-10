@extends('layouts.public')

@section('content')
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">

        <div class="position-absolute container top-0 start-50 translate-middle-x px-3 pt-4 w-100 "
            style="z-index: 10;">
            <nav class="navbar navbar-expand-md border border-white rounded-5 glass-navbar px-4 py-2 w-100">
                <a class="navbar-brand" href="/">
                    @if($tenant->logo)
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" style="height: 40px; width: auto; max-width: 120px; object-fit: contain;">
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
                        <li class="nav-item">
                            <a href="/" class="nav-link active rounded-5 decoration-0"
                                style="color: inherit; text-decoration: none;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="/publications" class="nav-link rounded-5 decoration-0"
                                style="color: inherit; text-decoration: none;">Publications</a>
                        </li>
                        <li class="nav-item">
                            <a href="/resources" class="nav-link rounded-5 decoration-0"
                                style="color: inherit; text-decoration: none;">Resources</a>
                        </li>
                        <li class="nav-item">
                            <a href="/blogs" class="nav-link rounded-5 decoration-0"
                                style="color: inherit; text-decoration: none;">Blogs</a>
                        </li>
                        <li class="nav-item">
                            <a href="/marketplace" class="nav-link rounded-5 decoration-0" style="color: inherit; text-decoration: none;">Store</a>
                        </li>
                        @if($tenant->orcid_url)
                        <li class="nav-item">
                            <a href="{{ $tenant->orcid_url }}" target="_blank" class="nav-link rounded-5 decoration-0"
                                style="color: inherit; text-decoration: none;">ORCID</a>
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

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active position-relative">
                @if($tenant->home_bg_image)
                    <img src="{{ asset('storage/' . $tenant->home_bg_image) }}" class="d-block w-100" alt="Slide 1" />
                @else
                    <img src="{{ asset('assets/boy.jpeg') }}" class="d-block w-100" alt="Slide 1" />
                @endif
                <div class="overlay"></div>
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-start text-start h-100"
                    style="transform: translateY(70px);">
                    <h1 class="display-4 fw-bold text-white text-wrap" style="max-width: 700px;">
                        {{ $tenant->owner_name }}
                    </h1>
                    <p class="lead text-white mb-4 text-wrap" style="max-width: 800px;">
                        Researcher and educator, founder of <strong>{{ $tenant->name }}</strong>,
                        committed to bridging students and resources with innovative digital tools.
                    </p>
                    <a href="#owner" class="btn btn-primary rounded-pill px-4 py-2">Know More About Me</a>
                </div>
            </div>

            <div class="carousel-item position-relative">
                <img src="{{ asset('assets/group_students.jpeg') }}" class="d-block w-100" alt="Slide 2" />
                <div class="overlay"></div>
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-start text-start h-100"
                    style="transform: translateY(70px);">
                    <h1 class="display-4 fw-bold text-white">All Your Learning Tools <br>In One Place</h1>
                    <p class="lead text-white mb-4">Seamless access to courses, assignments, and resources anytime.</p>
                    <a href="/courses" class="btn btn-primary rounded-pill px-4 py-2">Explore Now</a>
                </div>
            </div>

            <div class="carousel-item position-relative">
                <img src="{{ asset('assets/studious_gril.jpg') }}" class="d-block w-100" alt="Slide 3" />
                <div class="overlay"></div>
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-start text-start h-100"
                    style="transform: translateY(70px);">
                    <h1 class="display-4 fw-bold text-white">Empower Students & <br>Educators</h1>
                    <p class="lead text-white mb-4">Collaborate, learn, and grow in a unified academic ecosystem.</p>
                    <a href="/register" class="btn btn-primary rounded-pill px-4 py-2">Get Started</a>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container mt-4 position-relative " style="transform: translateY(-100px); z-index: 1050;">
        <div class="row g-3 px-3 slide-in">
            <div class="col-12 col-md-4">
                <div class="bg-danger text-white p-4 rounded h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="iconify" data-icon="material-symbols-light:book-ribbon" data-width="30"
                            data-height="30"></span>
                        <h4 class="mb-0">Books & Library</h4>
                    </div>
                    <p>
                        Access a rich digital library with thousands of books, research papers, and reference materials
                        to support your academic journey.
                    </p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="bg-primary text-white p-4 rounded h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="iconify" data-icon="mdi:account-tie" data-width="30" data-height="30"></span>
                        <h4 class="mb-0">Special Education</h4>
                    </div>
                    <p>
                        Tailored resources and tools to support students with diverse learning needs and create
                        inclusive learning environments.
                    </p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="text-white p-4 rounded h-100" id="professional_course">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="iconify" data-icon="mdi:school-outline" data-width="30" data-height="30"></span>
                        <h4 class="mb-0">Professional Courses</h4>
                    </div>
                    <p>
                        Enroll in certified professional courses designed to enhance your skills and prepare you for
                        real-world careers and industries.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5 p-5 rounded" id="owner"
        style="background-color: rgb(239, 238, 238); transform: translateY(-70px);">
        <div class="row align-items-center">
            <div class="col-12 col-lg-4 text-center mb-4 mb-lg-0">
                @if($tenant->avatar)
                    <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="{{ $tenant->owner_name }}" class="owner-img shadow-lg">
                @else
                    <img src="{{ asset('assets/boy.jpeg') }}" alt="{{ $tenant->owner_name }}" class="owner-img shadow-lg">
                @endif
            </div>

            <div class="col-12 col-lg-8">
                <h2 class="fw-bold mb-2">{{ $tenant->owner_name }}</h2>
                <h5 class="text-muted mb-3">Researcher & Founder</h5>
                <p class="lead fs-6">{{ Str::limit($tenant->bio, 180) }}</p>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    @if($tenant->phone)
                    <a href="tel:{{ $tenant->phone }}" class="btn btn-danger rounded-pill px-4">
                        Contact
                    </a>
                    @endif
                    @if($tenant->orcid_url)
                    <a href="{{ $tenant->orcid_url }}" target="_blank" class="btn btn-secondary rounded-pill px-4">
                        ORCID
                    </a>
                    @endif
                    
                    @php
                        $consultation = \App\Models\Consultation::where('tenant_id', $tenant->id)->where('is_active', true)->first();
                    @endphp
                    @if($consultation)
                    <a href="{{ route('tenant.consultation.book', ['tenant' => $tenant->subdomain]) }}" class="btn btn-success text-white fw-bold shadow-sm rounded-pill px-4">
                        <i class="bi bi-calendar-event me-2"></i> Book Consultation
                    </a>
                    @endif

                    <a href="#" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal"
                        data-bs-target="#aboutModal">
                        Check Profile →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg m-2">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="aboutModalLabel">About {{ $tenant->owner_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="lead fs-5" id="bio-full">{!! nl2br(e($tenant->bio)) !!}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <section class="container mb-5" id="box_overlay">
        <div class="row g-5">
            <div class="col-12 col-lg-7 pe-lg-5 px-5"> <h2 class="mb-4" style="color: rgb(36, 56, 111);">What We Offer</h2>

                <p style="text-align: justify;">
                    Our academic suite is designed to provide a comprehensive platform that empowers students,
                    educators, and institutions.
                    With a focus on accessibility, flexibility, and real-world readiness, we deliver tools and resources
                    that transform traditional learning into a modern, connected experience.
                </p>

                <ul class="list-unstyled mx-0" style="text-align: justify; max-width: 650px;">
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <span class="text-primary fw-bold">✔️</span>
                        <span>Access a rich digital library of books, journals, and learning resources.</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <span class="text-primary fw-bold">✔️</span>
                        <span>Join virtual classrooms and collaborate with teachers in real time.</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <span class="text-primary fw-bold">✔️</span>
                        <span>Earn recognized certifications to enhance your academic and professional profile.</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <span class="text-primary fw-bold">✔️</span>
                        <span>Connect with a diverse learning community for peer support and growth.</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <span class="text-primary fw-bold">✔️</span>
                        <span>Manage schedules, assignments, and deadlines with smart planning tools.</span>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-lg-5 text-center text-lg-end px-5">
                <img src="{{ asset('assets/library.jpeg') }}" alt="What we offer" class="img-fluid rounded shadow"
                    style="max-width: 100%; height: auto;">
            </div>
        </div>
    </section>

    @if($blogs && $blogs->count() > 0)
    <section style="background-color: rgb(249, 245, 245);" class="p-4">
        <div class="container my-5">
            <h2 class="text-center mb-4">From the Blog</h2>
            <div class="row gap-3 gap-md-0">
                @foreach($blogs as $blog)
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        @if($blog->cover_image)
                            <img src="{{ asset('storage/' . $blog->cover_image) }}" class="card-img-top" alt="Blog Image" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/young-student-learning-library.jpg') }}" class="card-img-top" alt="Blog Image" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $blog->title }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($blog->excerpt ?? wp_strip_all_tags($blog->content), 100) }}</p>
                            <div class="mt-auto">
                                <a href="/blogs/{{ $blog->id }}" class="btn btn-outline-primary btn-sm">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($resources) && $resources->count() > 0)
    <section class="py-5" style="background-color: #f0f4ff;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="color: rgb(36, 56, 111);">Resources</h2>
                <a href="/resources" class="btn btn-outline-primary rounded-pill px-4">View All</a>
            </div>
            <div class="row g-3">
                @foreach($resources as $resource)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                        <div class="card-body">
                            <h6 class="fw-semibold text-primary mb-1">{{ $resource->title }}</h6>
                            @if($resource->category)
                            <span class="badge bg-light text-secondary small mb-2">{{ $resource->category }}</span>
                            @endif
                            <p class="card-text text-muted small mb-3">{{ Str::limit($resource->description, 120) }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            @if($resource->file_path)
                            <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" download class="btn btn-outline-success btn-sm rounded-pill px-3">
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
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="py-5 position-relative text-white" id="testimonial" style="background-image: url('{{ asset('assets/young-student-learning-library.jpg') }}');">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger opacity-75"></div>

        <div class="container position-relative text-center">
            <h3 class="mb-5 fw-bold display-4 display-md-5 display-lg-4 text-center">
                What Students Say About Us
            </h3>

            <div class="container row mx-auto g-4 justify-content-center" style="overflow-x: hidden;">
                <div class="col-md-5">
                    <div class="bg-white text-dark p-4 rounded shadow">
                        <img src="{{ asset('assets/girl.jpeg') }}" alt="Student Photo" class="rounded-circle mx-auto d-block mb-3"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="fw-semibold mb-3">"The resources provided were top-notch. The learning environment made it easy for me to focus and grow."</h5>
                        <p class="mb-0 fw-medium">Mariam Yusuf</p>
                        <p class="text-muted small mb-0">University of Lagos</p>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="bg-white text-dark p-4 rounded shadow">
                        <img src="{{ asset('assets/boy.jpeg') }}" alt="Student Photo" class="rounded-circle mx-auto d-block mb-3"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="fw-semibold mb-3">"From virtual libraries to expert mentors, this platform transformed the way I learn and engage with my course."</h5>
                        <p class="mb-0 fw-medium">James Eze</p>
                        <p class="text-muted small mb-0">Obafemi Awolowo University</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="position-relative text-white">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -2; background-color: rgb(243, 243, 243);"></div>

        <div class="container py-5 px-5">
            <div class="row align-items-center" style="overflow-x: hidden;">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3 " style="color: rgb(36, 56, 111);">Request A Resource</h2>
                    <p class="mb-0 text-dark">
                        Need access to valuable materials or support tools? Submit your request and let us help you get
                        exactly what you need to succeed.
                    </p>
                </div>

                <div class="col-lg-6">
                    @if(session('success'))
                        <div class="alert alert-success fs-6 text-dark border-0 rounded-3 mb-4 shadow-sm">
                            <i class="bi bi-check-circle-fill text-success me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    <form action="/resource-request" method="POST" class="row g-3 g-lg-4 justify-content-center">
                        @csrf
                        <div class="col-md-6 ">
                            <input type="text" name="first_name" required
                                class="form-control bg-transparent border-bottom border-dark text-dark rounded p-3 "
                                placeholder="First Name" />
                        </div>
                        <div class="col-md-6 ">
                            <input type="text" name="last_name" required
                                class="form-control bg-transparent border-bottom border-dark text-dark rounded p-3 "
                                placeholder="Last Name" />
                        </div>

                        <div class="col-md-6 ">
                            <input type="text" name="phone"
                                class="form-control bg-transparent border-bottom border-dark text-dark rounded p-3 "
                                placeholder="Phone" />
                        </div>
                        <div class="col-md-6 ">
                            <input type="email" name="email" required
                                class="form-control bg-transparent border-bottom border-dark text-dark rounded p-3  "
                                placeholder="Email Address" />
                        </div>
                        <div class="col-12">
                            <textarea rows="3" name="message" required
                                class="form-control bg-transparent border-bottom border-dark text-dark rounded p-3"
                                placeholder="What resource are you requesting for?"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger text-white fw-medium px-4 py-2 rounded-pill">
                                Request Resource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light pt-5 pb-4">
        <div class="container px-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Have Questions?</h5>
                    <ul class="list-unstyled">
                        @if($tenant->address)
                        <li class="mb-2">
                            <span class="iconify me-2" data-icon="mdi:map-marker"></span>
                            {{ $tenant->address }}
                        </li>
                        @endif
                        @if($tenant->phone)
                        <li class="mb-2">
                            <span class="iconify me-2" data-icon="mdi:phone"></span>
                            {{ $tenant->phone }}
                        </li>
                        @endif
                        @if($tenant->email)
                        <li>
                            <span class="iconify me-2" data-icon="mdi:email"></span>
                            {{ $tenant->email }}
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="/publications" class="text-light text-decoration-none">Publications</a></li>
                        <li><a href="/resources" class="text-light text-decoration-none">Resources</a></li>
                        <li><a href="/blogs" class="text-light text-decoration-none">Blog</a></li>
                        <li><a href="/marketplace" class="text-light text-decoration-none">Store</a></li>
                        @if($tenant->orcid_url)
                        <li><a href="{{ $tenant->orcid_url }}" target="_blank" class="text-light text-decoration-none">ORCID</a></li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Connect With Us</h5>
                    @if(is_array($tenant->social_links) && count($tenant->social_links))
                    <div>
                        @if(!empty($tenant->social_links['twitter']))
                        <a href="{{ $tenant->social_links['twitter'] }}" class="text-light me-3 fs-5" target="_blank">
                            <span class="iconify" data-icon="mdi:twitter"></span>
                        </a>
                        @endif
                        @if(!empty($tenant->social_links['facebook']))
                        <a href="{{ $tenant->social_links['facebook'] }}" class="text-light me-3 fs-5" target="_blank">
                            <span class="iconify" data-icon="mdi:facebook"></span>
                        </a>
                        @endif
                        @if(!empty($tenant->social_links['instagram']))
                        <a href="{{ $tenant->social_links['instagram'] }}" class="text-light fs-5" target="_blank">
                            <span class="iconify" data-icon="mdi:instagram"></span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-center mt-4 small">
                &copy; {{ date('Y') }} {{ $tenant->name }}. All rights reserved
            </div>
        </div>
    </footer>
@endsection