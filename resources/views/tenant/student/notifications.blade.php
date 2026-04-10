@extends('layouts.admin')

@section('sidebar-nav')
<nav>
    <a href="{{ route('tenant.student.dashboard', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.dashboard') ? 'active' : '' }} m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="{{ route('tenant.student.courses', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.courses') ? 'active' : '' }} m-2">
        <i class="bi bi-book me-2"></i> Courses
    </a>
    <a href="{{ route('tenant.student.assignments', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.assignments') ? 'active' : '' }} m-2">
        <i class="bi bi-file-earmark-text me-2"></i> Assignments
    </a>
    <a href="{{ route('tenant.student.notifications', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.student.notifications') ? 'active' : '' }} m-2">
        <i class="bi bi-bell me-2"></i> Notifications
    </a>
    <a href="{{ route('tenant.marketplace.index', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->routeIs('tenant.marketplace*') ? 'active' : '' }} m-2">
        <i class="bi bi-shop me-2"></i> Store
    </a>
    <a href="{{ route('tenant.resources', ['tenant' => $tenant->subdomain]) }}" class="{{ request()->is('resources*') ? 'active' : '' }} m-2">
        <i class="bi bi-folder2-open me-2"></i> Resources
    </a>
    <hr class="border-white opacity-25 mx-3">
    <a href="{{ route('tenant.home', ['tenant' => $tenant->subdomain]) }}" class="m-2" target="_blank">
        <i class="bi bi-box-arrow-up-right me-2"></i> Portal Home
    </a>
    <form method="POST" action="{{ route('tenant.logout', ['tenant' => $tenant->subdomain]) }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">My Notifications</h3>
            @if($notifications->count() > 0)
                <span class="badge bg-primary rounded-pill">{{ $notifications->count() }} Total</span>
            @endif
        </div>

        <ul class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-start {{ $notification->read_at ? 'bg-light text-muted' : '' }}">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                        <div class="mt-1 small text-muted">
                            <i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if(empty($notification->read_at))
                        <div class="d-flex flex-column align-items-end gap-2">
                            <span class="badge bg-danger rounded-pill">New</span>
                            <form action="{{ route('tenant.student.notifications.read', ['tenant' => $tenant->subdomain, 'notification' => $notification->id]) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem;"><i class="bi bi-check2-all"></i> Mark Read</button>
                            </form>
                        </div>
                    @else
                        <span class="badge bg-secondary text-white rounded-pill">Read</span>
                    @endif
                </li>
            @empty
                <li class="list-group-item text-center text-muted py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted mb-3 d-block"></i>
                    No notifications available.
                </li>
            @endforelse
        </ul>

        @if($notifications->hasPages())
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
