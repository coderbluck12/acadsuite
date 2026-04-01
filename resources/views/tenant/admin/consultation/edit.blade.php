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
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Weekly Availability</label>
                        <div class="card border border-light shadow-sm">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @php
                                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                        $availability = is_array($consultation->availability) ? $consultation->availability : json_decode($consultation->availability ?? '{}', true);
                                    @endphp
                                    @foreach($days as $day)
                                        @php
                                            $dayConfig = $availability[$day] ?? ['enabled' => false, 'start' => '09:00', 'end' => '17:00'];
                                            $isEnabled = isset($dayConfig['enabled']) && $dayConfig['enabled'] == 1;
                                        @endphp
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="availability[{{ $day }}][enabled]" value="1" id="day_{{ $day }}" {{ $isEnabled ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="day_{{ $day }}">{{ $day }}</label>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="time" class="form-control form-control-sm border-0 bg-light" name="availability[{{ $day }}][start]" value="{{ $dayConfig['start'] ?? '09:00' }}" required>
                                                <span class="text-muted small px-1">to</span>
                                                <input type="time" class="form-control form-control-sm border-0 bg-light" name="availability[{{ $day }}][end]" value="{{ $dayConfig['end'] ?? '17:00' }}" required>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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
                    <li>Set your consultation <strong>fee</strong> and select your <strong>weekly available hours</strong>.</li>
                    <li>Students will see a "Book Consultation" button on your profile.</li>
                    <li>They will pick an available time slot dynamically calculated from your weekly schedule.</li>
                    <li>They will securely pay the specified fee. You will receive an email and they will lock in their slot reservation.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
