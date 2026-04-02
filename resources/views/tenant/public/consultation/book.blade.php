@extends('layouts.public')

@section('content')
<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-calendar-check-fill me-2"></i> Book Consultation</h4>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        @if($tenant->avatar)
                            <img src="{{ asset('storage/' . $tenant->avatar) }}" class="rounded-circle shadow-sm mb-3" width="80" height="80" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/boy.jpeg') }}" class="rounded-circle shadow-sm mb-3" width="80" height="80" style="object-fit: cover;">
                        @endif
                        <h5 class="fw-bold">{{ $tenant->owner_name }}</h5>
                        <p class="text-muted">Consultation Booking</p>
                    </div>

                    @if($consultation->instructions)
                    <div class="alert alert-secondary border-0 mb-4 small rounded-3 p-3 text-dark">
                        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-1"></i> Instructions:</h6>
                        {!! nl2br(e($consultation->instructions)) !!}
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <span class="fw-semibold text-muted">Consultation Fee</span>
                        <h4 class="fw-bold m-0 text-primary">₦{{ number_format($consultation->fee, 2) }}</h4>
                    </div>

                    @if($consultation->fee > 0)
                    <form id="paymentForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Select Date</label>
                                <input type="date" id="booking_date" class="form-control py-2" required min="{{ date('Y-m-d') }}" onchange="fetchTimeSlots()">
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label fw-semibold">Select Time Slot</label>
                                <select id="booking_time" class="form-select py-2" required disabled>
                                    <option value="">Choose a date first...</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Your Email Address</label>
                            <input type="email" id="email-address" class="form-control py-2" required placeholder="Enter email for receipt" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" onclick="payWithPaystack(event)" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm">
                                Pay ₦{{ number_format($consultation->fee, 2) }} to Book
                            </button>
                        </div>
                        
                        @if(env('APP_ENV') === 'local' || !env('PAYSTACK_PUBLIC_KEY'))
                        <div class="alert alert-info mt-4 small border-0 shadow-sm text-center">
                            <strong><i class="bi bi-info-circle me-1"></i> Testing Mode Active</strong><br>
                            Click below to test end-to-end scenarios without real payment.
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                <button type="button" onclick="simulatePayment(true)" class="btn btn-sm btn-outline-success">Simulate Success</button>
                                <button type="button" onclick="simulatePayment(false)" class="btn btn-sm btn-outline-danger">Simulate Failure</button>
                            </div>
                        </div>
                        @endif
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="text-muted text-decoration-none small">Cancel</a>
                        </div>
                    </form>
                    @else
                    <div class="d-grid mt-4">
                        <a href="{{ route('tenant.consultation.verify', ['tenant' => $tenant->subdomain, 'simulate_success' => 1, 'consultation_id' => $consultation->id]) }}" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm">
                            Proceed to Book (Free)
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @if($consultation->fee > 0)
            <div class="text-center mt-4 text-muted small">
                <p><i class="bi bi-shield-check text-success me-1"></i> Payments are securely processed by Paystack.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if($consultation->fee > 0)
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    function fetchTimeSlots() {
        let date = document.getElementById('booking_date').value;
        let timeSelect = document.getElementById('booking_time');
        
        if (!date) return;
        
        timeSelect.innerHTML = '<option value="">Loading slots...</option>';
        timeSelect.disabled = true;

        let slotsBase = `{{ route('tenant.consultation.slots', ['tenant' => $tenant->subdomain]) }}`;
        let slotsSep = slotsBase.includes('?') ? '&' : '?';
        fetch(`${slotsBase}${slotsSep}date=${date}&consultation_id={{ $consultation->id }}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '';
                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        let option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                    timeSelect.disabled = false;
                } else {
                    timeSelect.innerHTML = '<option value="">No availability on this date</option>';
                    timeSelect.disabled = true;
                    if (data.debug) {
                        console.log("Slot fetch debug info:", data.debug);
                    }
                }
            })
            .catch(err => {
                console.error('Error fetching slots:', err);
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
            });
    }

    function payWithPaystack(e) {
        e.preventDefault();
        
        let email = document.getElementById('email-address').value;
        let bookingDate = document.getElementById('booking_date').value;
        let bookingTime = document.getElementById('booking_time').value;

        if (!email || !bookingDate || !bookingTime) {
            alert('Please complete all form fields including Date and Time selection.');
            return;
        }

        var customRef = 'CONS_' + Math.floor((Math.random() * 1000000000) + 1);
        let handler = PaystackPop.setup({
            key: '{{ env("PAYSTACK_PUBLIC_KEY", "pk_test_placeholder") }}', 
            email: email,
            amount: {{ $consultation->fee * 100 }}, 
            currency: "NGN",
            reference: customRef, 
            callback: function(response) {
                let reference = response.reference || response.trxref || response.trans || customRef;
                let baseUrl = "{{ route('tenant.consultation.verify', ['tenant' => $tenant->subdomain]) }}";
                let separator = baseUrl.indexOf('?') !== -1 ? '&' : '?';
                let verifyUrl = baseUrl + separator + "reference=" + encodeURIComponent(reference) + "&consultation_id={{ $consultation->id }}&email=" + encodeURIComponent(email) + "&booking_date=" + encodeURIComponent(bookingDate) + "&booking_time=" + encodeURIComponent(bookingTime);
                window.location.href = verifyUrl;
            },
            onClose: function() {
                alert('Payment window closed.');
            }
        });

        handler.openIframe();
    }

    function simulatePayment(isSuccess) {
        var customRef = 'TEST_' + Math.floor((Math.random() * 1000000000) + 1);
        let email = document.getElementById('email-address').value;
        let bookingDate = document.getElementById('booking_date').value;
        let bookingTime = document.getElementById('booking_time').value;

        if (!email || !bookingDate || !bookingTime) {
            alert("Please complete all fields first.");
            return;
        }
        
        let baseUrl = "{{ route('tenant.consultation.verify', ['tenant' => $tenant->subdomain]) }}";
        let separator = baseUrl.indexOf('?') !== -1 ? '&' : '?';
        let flag = isSuccess ? 'simulate_success=1' : 'simulate_failure=1';
        let verifyUrl = baseUrl + separator + "reference=" + encodeURIComponent(customRef) + "&consultation_id={{ $consultation->id }}&email=" + encodeURIComponent(email) + "&booking_date=" + encodeURIComponent(bookingDate) + "&booking_time=" + encodeURIComponent(bookingTime) + "&" + flag;
        
        window.location.href = verifyUrl;
    }
</script>
@endif
@endsection
