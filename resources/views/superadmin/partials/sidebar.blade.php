<nav>
    <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }} m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="{{ route('superadmin.academics.index') }}" class="{{ request()->routeIs('superadmin.academics*') ? 'active' : '' }} m-2">
        <i class="bi bi-people me-2"></i> All Academics
    </a>
    <a href="{{ route('superadmin.withdrawals.index') }}" class="{{ request()->routeIs('superadmin.withdrawals*') ? 'active' : '' }} m-2">
        <i class="bi bi-cash-stack me-2"></i> Withdrawals
    </a>
    <a href="{{ route('superadmin.settings.index') }}" class="{{ request()->routeIs('superadmin.settings*') ? 'active' : '' }} m-2">
        <i class="bi bi-gear-fill me-2"></i> Settings
    </a>
    <hr class="border-white opacity-25 mx-3">
    <form method="POST" action="{{ route('superadmin.logout') }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
