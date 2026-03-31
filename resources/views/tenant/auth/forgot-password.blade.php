@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-container { width: 100%; max-width: 400px; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .btn-custom { border-radius: 8px; font-weight: 600; padding: 0.75rem; }
        .form-control { border-radius: 8px; padding: 0.65rem 1rem; }
    </style>
@endpush

@section('body')
<div class="container d-flex justify-content-center">
    <div class="login-container">
        <div class="text-center mb-4">
            @if(isset($tenant) && $tenant->logo)
                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" width="100">
            @else
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" width="100">
            @endif
        </div>
        <h4 class="fw-bold text-center mb-2">Forgot Password</h4>
        <p class="text-muted text-center small mb-4">Enter your email and we'll send you a link to reset your password.</p>

        @if(session('status'))
            <div class="alert alert-success py-2">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('tenant.password.email', ['tenant' => $tenant->subdomain ?? '']) }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary btn-custom w-100 mb-3">Send Reset Link</button>
            <div class="text-center">
                <a href="{{ route('tenant.login', ['tenant' => $tenant->subdomain ?? '']) }}" class="text-decoration-none text-muted fw-semibold">&larr; Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
