@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <div class="card shadow profile-card p-4">
        <h3 class="text-center mb-4">Profile Management</h3>
        <form method="POST" action="{{ route('tenant.admin.profile.update', ['tenant' => $tenant->subdomain]) }}" enctype="multipart/form-data">
            @csrf
            <div class="text-center mb-3">
                @if($tenant->avatar)
                    <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="Profile" class="rounded-circle border" style="width:120px;height:120px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center border" style="width:120px;height:120px;">
                        <span class="text-white fw-bold" style="font-size:3rem;">{{ strtoupper(substr($tenant->owner_name,0,1)) }}</span>
                    </div>
                @endif
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Change Profile Picture</label>
                    <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Logo — Light Background <small class="text-muted">(for public portal navbars)</small></label>
                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                    @if($tenant->logo)
                        <div class="mt-1"><img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" style="height:36px;"> <small class="text-muted ms-2">Current</small></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Logo — Dark/Colored Background <small class="text-muted">(for admin dashboard sidebar)</small></label>
                    <input type="file" name="logo_dark" class="form-control form-control-sm" accept="image/*">
                    @if($tenant->logo_dark)
                        <div class="mt-1"><img src="{{ asset('storage/' . $tenant->logo_dark) }}" alt="Dark Logo" style="height:36px;background:#333;padding:4px;border-radius:4px;"> <small class="text-muted ms-2">Current</small></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dashboard Background Image</label>
                    <input type="file" name="dashboard_bg_image" class="form-control form-control-sm" accept="image/*">
                    @if($tenant->dashboard_bg_image)
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="remove_dashboard_bg" value="1" id="removeDashboardBg">
                        <label class="form-check-label text-danger small" for="removeDashboardBg">
                            Remove current background
                        </label>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Home Page Background Image</label>
                    <input type="file" name="home_bg_image" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Your Full Name</label>
                    <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $tenant->owner_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Suite Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $tenant->name) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Bio</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio', $tenant->bio) }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" value="{{ $tenant->email }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ORCID Link</label>
                    <input type="url" name="orcid_url" class="form-control" value="{{ old('orcid_url', $tenant->orcid_url) }}" placeholder="https://orcid.org/0000-0000-0000-0000">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $tenant->address) }}">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                     <label class="form-label fw-semibold">Custom Domain</label>
                     <input type="text" name="custom_domain" class="form-control" value="{{ old('custom_domain', $tenant->custom_domain) }}" placeholder="e.g. platform.myschool.com">
                     <div class="form-text">Point your domain's CNAME or A record to our server's IP address.</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Update Profile</button>
        </form>
    </div>
</div>
@endsection
