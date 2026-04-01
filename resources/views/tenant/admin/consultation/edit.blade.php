@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Consultation Settings</h4>
    </div>
    
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <form action="{{ route('tenant.admin.consultation.update', ['tenant' => $tenant->subdomain]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 form-check form-switch p-0 d-flex justify-content-between align-items-center border-bottom pb-3">
                        <label class="form-check-label fw-bold m-0" for="isActive">Enable Consultation Bookings</label>
                        <input class="form-check-input ms-3 fs-5" type="checkbox" role="switch" name="is_active" id="isActive" value="1" {{ $consultation->is_active ? 'checked' : '' }}>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Consultation Fee (₦)</label>
                        <input type="number" step="0.01" name="fee" class="form-control py-2" value="{{ old('fee', $consultation->fee) }}" required min="0">
                        <small class="text-muted">Set to 0 for free consultations.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Calendly Booking Link</label>
                        <input type="url" name="calendly_link" class="form-control py-2" value="{{ old('calendly_link', $consultation->calendly_link) }}" placeholder="https://calendly.com/your-name/30min" required>
                        <small class="text-muted">Past in your Calendly Event URL. This will be provided to the student after payment.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Special Instructions (Optional)</label>
                        <textarea name="instructions" class="form-control" rows="4" placeholder="Any special instructions for the student before they book...">{{ old('instructions', $consultation->instructions) }}</textarea>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="card border-0 bg-light rounded-4 p-4 shadow-sm">
                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i> How it works</h5>
                <ol class="small text-muted ps-3 lh-lg mb-0">
                    <li>Set your consultation <strong>fee</strong> and paste your <strong>Calendly link</strong>.</li>
                    <li>Students will see a "Book Consultation" button on your profile.</li>
                    <li>They will pay the specified fee securely via the platform.</li>
                    <li>Only after successful payment will they be given your Calendly link to pick a time.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
