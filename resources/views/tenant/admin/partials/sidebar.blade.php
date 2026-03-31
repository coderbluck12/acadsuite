<nav>
    <a href="/admin/profile" class="{{ request()->routeIs('tenant.admin.profile') ? 'active' : '' }} m-2">
        <i class="bi bi-person-circle me-2"></i> Profile
    </a>
    <a href="/admin/students" class="{{ request()->routeIs('tenant.admin.students') ? 'active' : '' }} m-2">
        <i class="bi bi-people me-2"></i> Manage Students
    </a>
    <a href="/admin/publications" class="{{ request()->routeIs('tenant.admin.publications*') ? 'active' : '' }} m-2">
        <i class="bi bi-journal-text me-2"></i> Publications
    </a>
    <a href="/admin/resources" class="{{ request()->routeIs('tenant.admin.resources*') ? 'active' : '' }} m-2">
        <i class="bi bi-folder2-open me-2"></i> Resources
    </a>
    <a href="/admin/courses" class="{{ request()->routeIs('tenant.admin.courses*') ? 'active' : '' }} m-2">
        <i class="bi bi-book me-2"></i> Courses
    </a>
    <a href="/admin/blogs" class="{{ request()->routeIs('tenant.admin.blogs*') ? 'active' : '' }} m-2">
        <i class="bi bi-pencil-square me-2"></i> Blogs
    </a>
    <a href="/admin/assignments" class="{{ request()->routeIs('tenant.admin.assignments*') ? 'active' : '' }} m-2">
        <i class="bi bi-file-earmark-text me-2"></i> Assignments
    </a>
    <a href="/admin/plans" class="{{ request()->routeIs('tenant.admin.plans') ? 'active' : '' }} m-2 text-warning fw-bold">
        <i class="bi bi-star-fill me-2"></i> Plans & Billing
    </a>
    <hr class="border-white opacity-25 mx-3">
    <a href="/admin/products" class="{{ request()->routeIs('tenant.admin.products*') ? 'active' : '' }} m-2">
        <i class="bi bi-cart3 me-2"></i> Marketplace
    </a>
    <a href="/admin/purchases" class="{{ request()->routeIs('tenant.admin.purchases*') ? 'active' : '' }} m-2">
        <i class="bi bi-cart-check me-2"></i> Purchases
    </a>
    <a href="/admin/wallet" class="{{ request()->routeIs('tenant.admin.wallet*') ? 'active' : '' }} m-2">
        <i class="bi bi-wallet2 me-2"></i> Wallet
    </a>
    <hr class="border-white opacity-25 mx-3">
    <a href="/" class="m-2" target="_blank">
        <i class="bi bi-box-arrow-up-right me-2"></i> View Portal
    </a>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
