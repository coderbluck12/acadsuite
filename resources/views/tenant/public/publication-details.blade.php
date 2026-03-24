@extends('layouts.public')

@section('content')
    <section class="hero" style="position: relative; background: url('{{ asset('assets/publications.jpg') }}') center/cover no-repeat; height: 40vh; display: flex; align-items: center; justify-content: center; color: white;">
        <nav class="position-absolute top-0 start-50 translate-middle-x px-3 pt-4 w-100" style="z-index: 10;">
            <div class="navbar border border-white rounded-5 glass-navbar px-4 py-2" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                <a class="navbar-brand" href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}">
                    @if($tenant->logo)
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" style="height: 40px; width: auto; max-width: 120px; object-fit: contain;">
                    @else
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width: 100px;">
                    @endif
                </a>
                <a href="{{ route('tenant.publications', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-light rounded-pill px-3 py-1 ms-auto">
                    &larr; Back to Publications
                </a>
            </div>
        </nav>
        <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(253, 46, 46, 0.6);"></div>
        <div class="hero-content" style="position: relative; z-index: 2; text-align: center; max-width: 800px; padding: 20px;">
            <h2 class="fw-bold">{{ $publication->title }}</h2>
        </div>
    </section>

    <section class="container my-5 px-4 min-vh-50">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-lg border-0 mb-4 p-4">
                    @if($publication->cover_image)
                        <img src="{{ asset('storage/' . $publication->cover_image) }}" class="rounded mb-4" alt="Cover Image" style="max-height: 400px; object-fit: cover; width: 100%;">
                    @endif
                    <h3 class="fw-bold text-primary mb-3">{{ $publication->title }}</h3>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-person-fill me-2"></i>Authors:</h6>
                        <p class="fs-5">{{ $publication->authors ?: $tenant->owner_name }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <h6 class="text-muted mb-2"><i class="bi bi-journal-check me-2"></i>Journal/Conference:</h6>
                            <p class="fs-5">{{ $publication->journal ?: 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="text-muted mb-2"><i class="bi bi-calendar-event me-2"></i>Year:</h6>
                            <p class="fs-5">{{ $publication->year ?: 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-file-text me-2"></i>Abstract:</h6>
                        <p class="fs-6 text-justify" style="line-height: 1.8;">
                            {{ $publication->abstract ?: 'No abstract provided.' }}
                        </p>
                    </div>

                    @if($publication->url)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2"><i class="bi bi-link-45deg me-2"></i>External Link / DOI:</h6>
                            <a href="{{ $publication->url }}" target="_blank" class="text-primary fs-6">{{ $publication->url }}</a>
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            @if($publication->access_type === 'download' && $publication->file_path)
                                <a href="{{ asset('storage/' . $publication->file_path) }}" target="_blank" class="btn btn-success px-4 rounded-pill">
                                    <i class="bi bi-download me-1"></i> Download PDF
                                </a>
                            @else
                                <button class="btn btn-secondary px-4 rounded-pill disabled">
                                    <i class="bi bi-eye me-1"></i> View Only
                                </button>
                                <span class="ms-2 text-muted small">This publication is view-only.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
