@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Consultation Bookings</h4>
        <a href="{{ route('tenant.admin.consultation.edit', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-primary btn-sm rounded-pill">
            <i class="bi bi-gear me-1"></i> Availability Settings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <p class="text-muted small mb-1">Total Bookings</p>
                <h3 class="fw-bold text-primary mb-0">{{ $bookings->total() }}</h3>
            </div>
        </div>
        <div class="col-sm-4 mt-3 mt-sm-0">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <p class="text-muted small mb-1">Today's Bookings</p>
                <h3 class="fw-bold text-success mb-0">{{ $todayCount }}</h3>
            </div>
        </div>
        <div class="col-sm-4 mt-3 mt-sm-0">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <p class="text-muted small mb-1">Upcoming (Next 7 Days)</p>
                <h3 class="fw-bold text-warning mb-0">{{ $upcomingCount }}</h3>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body py-2 px-4">
            <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
                <input type="hidden" name="tenant" value="{{ $tenant->subdomain }}">
                <div class="d-flex align-items-center gap-2">
                    <label class="small fw-semibold mb-0">Date:</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">Filter</button>
                @if(request('date'))
                    <a href="{{ route('tenant.admin.consultation.bookings', ['tenant' => $tenant->subdomain]) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Clear</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            <th class="text-end px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;font-size:14px;">
                                        {{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $booking->user->name ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $booking->user->email ?? '—' }}</td>
                            <td>
                                @if($booking->booking_date)
                                    <span class="badge bg-light text-dark border">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->booking_time)
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">{{ $booking->booking_time }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-success">₦{{ number_format($booking->amount_paid, 2) }}</td>
                            <td>
                                <span class="badge rounded-pill
                                    {{ $booking->status === 'paid' ? 'bg-success' : ($booking->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-outline-primary rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#sendLinkModal"
                                    data-booking-id="{{ $booking->id }}"
                                    data-student-name="{{ $booking->user->name ?? 'Student' }}"
                                    data-student-email="{{ $booking->user->email ?? '' }}"
                                    data-booking-date="{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') : '—' }}"
                                    data-booking-time="{{ $booking->booking_time ?? '—' }}">
                                    <i class="bi bi-envelope me-1"></i> Send Call Link
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                                No bookings found{{ request('date') ? ' for the selected date.' : ' yet.' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Send Call Link Modal --}}
<div class="modal fade" id="sendLinkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-send-fill text-primary me-2"></i>Send Call Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tenant.admin.consultation.send-link', ['tenant' => $tenant->subdomain]) }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="bg-light rounded-3 p-3 mb-3 small">
                        <p class="mb-1"><strong>To:</strong> <span id="modal-student-name"></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span id="modal-student-email"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="modal-booking-date"></span></p>
                        <p class="mb-0"><strong>Time:</strong> <span id="modal-booking-time"></span></p>
                    </div>
                    <input type="hidden" name="booking_id" id="modal-booking-id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Call / Meeting Link</label>
                        <input type="url" name="call_link" class="form-control" required
                            placeholder="e.g. https://meet.google.com/abc-xyz or https://zoom.us/j/...">
                        <small class="text-muted">Paste your Google Meet, Zoom, or any video call link.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Additional Message (Optional)</label>
                        <textarea name="message" class="form-control" rows="3"
                            placeholder="E.g. Please join 5 minutes early..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-envelope-fill me-1"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const sendLinkModal = document.getElementById('sendLinkModal');
    sendLinkModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('modal-booking-id').value   = btn.dataset.bookingId;
        document.getElementById('modal-student-name').textContent  = btn.dataset.studentName;
        document.getElementById('modal-student-email').textContent = btn.dataset.studentEmail;
        document.getElementById('modal-booking-date').textContent  = btn.dataset.bookingDate;
        document.getElementById('modal-booking-time').textContent  = btn.dataset.bookingTime;
    });
</script>
@endsection
