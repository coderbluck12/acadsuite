@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #1a237e, #3F51B5); min-height: 100vh; display: flex; align-items: center; }
        .login-card { background: white; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 400px; width: 100%; }
        .form-control:focus { border-color: #3F51B5; box-shadow: 0 0 0 3px rgba(63,81,181,0.15); }
    </style>
@endpush

@section('body')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="login-card p-4 p-md-5">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/logo.png') }}" height="60" alt="Pentagonware">
            <h4 class="fw-bold mt-3 mb-1">Pentagonware HQ</h4>
            <p class="text-muted small">Super Admin Login</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 text-sm">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-4">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2 rounded-pill">Login to HQ</button>
        </form>
        <div class="text-center mt-4 small text-muted">
            <a href="{{ route('landing.home') }}" class="text-decoration-none">← Back to AcadSuite</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
