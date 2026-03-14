@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #1a237e, #3F51B5); min-height: 100vh; display: flex; align-items: center; }
    </style>
@endpush

@section('body')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="text-center text-white px-4" style="max-width:600px;">
        <div class="mb-4" style="font-size:5rem;">🎉</div>
        <h1 class="fw-bold display-5 mb-3">Your Suite is Live!</h1>
        @if(session('subdomain'))
        <p class="lead opacity-90 mb-4">Your academic portal is ready at:</p>
        <a href="{{ session('portal_url') }}" target="_blank"
           class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold shadow mb-4">
            🌐 {{ session('subdomain') }}.{{ config('app.base_domain') }} →
        </a>
        @endif
        <p class="opacity-75 mb-4">Login with the email and password you registered with to access your admin panel and start adding publications, courses, and resources.</p>
        <a href="{{ route('landing.home') }}" class="btn btn-outline-light rounded-pill px-4">← Back to AcadSuite</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
