@extends('layouts.public')

@section('content')
<div class="container my-5 pt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 p-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h3 class="fw-bold mb-3">Booking Payment Successful!</h3>
                <p class="text-muted mb-4">
                    Thank you. Your payment was received successfully.<br>
                    Please click the button below to select your date and time via Calendly.
                </p>
                
                <div class="mb-4">
                    <a href="{{ $consultation->calendly_link }}" target="_blank" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                        <i class="bi bi-calendar-plus me-2"></i> Schedule Meeting
                    </a>
                </div>
                <div class="text-muted small">
                    @if($booking->payment_reference)
                    <p class="mb-0">Please note down your payment reference: <strong>{{ $booking->payment_reference }}</strong></p>
                    @endif
                    <p>Once you schedule the meeting, an invitation will be sent to your email by Calendly.</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="text-decoration-none text-muted fw-semibold">
                    &larr; Return to Homepage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
