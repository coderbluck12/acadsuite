@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .navbar { background: #3F51B5; padding: 0.75rem 1rem; }
        .navbar-brand, .navbar-brand:hover { color: white; font-weight: 600; font-size: 1.1rem; }
        .sidebar {
            height: 100vh; background: #3F51B5; color: white;
            padding-top: 20px; position: fixed; width: 220px;
            left: 0; top: 0; transition: left 0.3s ease; z-index: 1050;
        }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 11px 20px; margin-bottom: 4px; border-radius: 5px; transition: background 0.25s; font-size: 0.9rem; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); font-weight: 600; }
        .toggle-btn { display: none; font-size: 1.5rem; background: none; border: none; color: white; }
        .content { margin-left: 240px; padding: 20px; transition: margin-left 0.3s ease; min-height: 100vh; background: #f8f9fa; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #fff; object-fit: cover; }
        @media (max-width: 991px) {
            .sidebar { left: -220px; }
            .sidebar.open { left: 0; }
            .content { margin-left: 0; }
            .toggle-btn { display: inline-block; }
        }
        .card {
            margin: 50px auto;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.7s ease forwards;
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 6px rgba(13, 110, 253, 0.5);
        }
    </style>
    @stack('page-styles')
@endpush

@section('body')
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg fixed-top" @if(isset($tenant) && $tenant->dashboard_bg_image) style="background: rgba(63, 81, 181, 0.9);" @endif>
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                @if(isset($tenant) && $tenant->logo)
                    <img src="{{ asset('storage/' . $tenant->logo) }}" alt="logo" style="height:40px; width:auto; max-width:120px; object-fit:contain;">
                @else
                    <img src="{{ asset('assets/logo.png') }}" alt="logo" style="width:70px;">
                @endif
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <small class="text-white d-none d-md-inline">{{ isset($tenant) ? $tenant->owner_name : 'Super Admin' }}</small>
                @if(isset($tenant) && $tenant->avatar)
                    <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="Profile" class="profile-img">
                @else
                    <img src="{{ asset('assets/logo.png') }}" alt="Profile" class="profile-img">
                @endif
                <button class="toggle-btn" id="menuToggle"><i class="bi bi-list"></i></button>
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar" @if(isset($tenant) && $tenant->dashboard_bg_image) style="background: rgba(63, 81, 181, 0.9);" @endif>
        <div class="mb-4 text-center pt-2">
            @if(isset($tenant) && $tenant->logo)
                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="logo" style="height:50px; width:auto; max-width:120px; object-fit:contain;">
            @else
                <img src="{{ asset('assets/logo.png') }}" alt="logo" style="width:70px;">
            @endif
        </div>
        @yield('sidebar-nav')
    </aside>

    {{-- Content --}}
    <main class="content mt-5 pt-3" @if(isset($tenant) && $tenant->dashboard_bg_image) style="background-image: url('{{ asset('storage/' . $tenant->dashboard_bg_image) }}'); background-size: cover; background-attachment: fixed; background-position: center;" @endif>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('menuToggle');
        const sidebar   = document.getElementById('sidebar');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('open');
                const icon = toggleBtn.querySelector('i');
                icon.classList.toggle('bi-list');
                icon.classList.toggle('bi-x');
            });
        }
    </script>
    @stack('scripts')
@endsection
