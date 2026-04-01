@extends('layouts.public')

@section('content')
<div class="container my-5 pt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 p-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h3 class="fw-bold mb-3 mt-3 text-success">Consultation Confirmed!</h3>
                <p class="text-muted mb-4">
                    Thank you. Your payment was received successfully and your time slot is reserved!
                </p>
                
                <div class="mb-4 bg-light rounded-4 p-4 text-start shadow-sm border">
                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-calendar-event text-primary me-2"></i> Meeting Details</h5>
                    <p class="mb-2"><strong>Academic:</strong> {{ $tenant->owner_name }}</p>
                    <p class="mb-2"><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, jS F Y') }}</p>
                    <p class="mb-0"><strong>Time:</strong> {{ $booking->booking_time }}</p>
                </div>

                <div class="text-muted small">
                    @if($booking->payment_reference)
                    <p class="mb-2">Payment Reference: <strong>{{ $booking->payment_reference }}</strong></p>
                    @endif
                    <p>An email regarding further details and the link to join the actual call will be sent to you by the academic shortly.</p>
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
