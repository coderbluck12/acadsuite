<?php

use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\Landing\SuiteRegistrationController;
use App\Http\Controllers\SuperAdmin\SuperAdminAuthController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\AcademicsController;
use App\Http\Controllers\Tenant\Auth\TenantAuthController;
use App\Http\Controllers\Tenant\Public\HomeController;
use App\Http\Controllers\Tenant\Public\PublicationController;
use App\Http\Controllers\Tenant\Public\CourseController;
use App\Http\Controllers\Tenant\Public\BlogController;
use App\Http\Controllers\Tenant\Public\ResourceController as PublicResourceController;
use App\Http\Controllers\Tenant\Public\MarketplaceController;
use App\Http\Controllers\Tenant\Public\CheckoutController;
use App\Http\Controllers\Tenant\Admin\ProfileController;
use App\Http\Controllers\Tenant\Admin\StudentController;
use App\Http\Controllers\Tenant\Admin\AdminPublicationController;
use App\Http\Controllers\Tenant\Admin\AdminResourceController;
use App\Http\Controllers\Tenant\Admin\AdminCourseController;
use App\Http\Controllers\Tenant\Admin\AdminBlogController;
use App\Http\Controllers\Tenant\Admin\AdminAssignmentController;
use App\Http\Controllers\Tenant\Admin\AdminProductController;
use App\Http\Controllers\Tenant\Admin\AdminWalletController;
use App\Http\Controllers\Tenant\Student\StudentDashboardController;
use App\Http\Controllers\Tenant\Student\StudentNotificationController;
use App\Http\Controllers\Tenant\Student\StudentAssignmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Helper closure — all tenant routes reused in both domain & localhost mode
|--------------------------------------------------------------------------
*/
$tenantRoutes = function () {
    // ----- Public Pages -----
    Route::get('/', [HomeController::class, 'index'])->name('tenant.home');
    Route::get('/publications', [PublicationController::class, 'index'])->name('tenant.publications');
    Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('tenant.publications.show');
    Route::get('/courses', [CourseController::class, 'index'])->name('tenant.courses');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('tenant.courses.show');
    Route::get('/blogs', [BlogController::class, 'index'])->name('tenant.blogs');
    Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('tenant.blogs.show');
    Route::get('/resources', [PublicResourceController::class, 'index'])->name('tenant.resources');
    Route::post('/resource-request', [PublicResourceController::class, 'request'])->name('tenant.resource.request');
    
    // ----- Marketplace & Checkout -----
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('tenant.marketplace.index');
    Route::get('/marketplace/{product}', [MarketplaceController::class, 'show'])->name('tenant.marketplace.show');
    Route::get('/checkout/verify', [CheckoutController::class, 'verify'])->name('tenant.checkout.verify');
    Route::get('/checkout/{product}', [CheckoutController::class, 'checkout'])->name('tenant.checkout.index');
    Route::get('/checkout/{product}/success', [CheckoutController::class, 'success'])->name('tenant.checkout.success');
    Route::get('/checkout/{product}/download', [CheckoutController::class, 'download'])->name('tenant.checkout.download');

    // ----- Auth -----
    Route::get('/login', [TenantAuthController::class, 'showLogin'])->name('tenant.login');
    Route::post('/login', [TenantAuthController::class, 'login'])->name('tenant.login.post');
    Route::get('/register', [TenantAuthController::class, 'showRegister'])->name('tenant.register');
    Route::post('/register', [TenantAuthController::class, 'register'])->name('tenant.register.post');
    Route::post('/logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');

    // Password Reset
    Route::get('/forgot-password', [\App\Http\Controllers\Tenant\Auth\TenantPasswordResetController::class, 'showLinkRequestForm'])->name('tenant.password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Tenant\Auth\TenantPasswordResetController::class, 'sendResetLinkEmail'])->name('tenant.password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Tenant\Auth\TenantPasswordResetController::class, 'showResetForm'])->name('tenant.password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Tenant\Auth\TenantPasswordResetController::class, 'reset'])->name('tenant.password.update');

    // ----- Admin Panel -----
    Route::prefix('admin')->middleware('tenant_admin')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('tenant.admin.profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('tenant.admin.profile.update');

        Route::get('/students', [StudentController::class, 'index'])->name('tenant.admin.students');
        Route::patch('/students/{user}/approve', [StudentController::class, 'approve'])->name('tenant.admin.students.approve');
        Route::patch('/students/{user}/reject', [StudentController::class, 'reject'])->name('tenant.admin.students.reject');

        Route::resource('publications', AdminPublicationController::class, ['as' => 'tenant.admin']);
        Route::resource('resources', AdminResourceController::class, ['as' => 'tenant.admin']);
        Route::resource('courses', AdminCourseController::class, ['as' => 'tenant.admin']);
        Route::resource('blogs', AdminBlogController::class, ['as' => 'tenant.admin']);
        Route::resource('assignments', AdminAssignmentController::class, ['as' => 'tenant.admin']);
        Route::patch('assignments/{assignment}/submissions/{submission}/grade', [AdminAssignmentController::class, 'grade'])->name('tenant.admin.assignments.grade');
        
        Route::resource('products', AdminProductController::class, ['as' => 'tenant.admin']);
        Route::get('/wallet', [AdminWalletController::class, 'index'])->name('tenant.admin.wallet');
        Route::post('/wallet/withdraw', [AdminWalletController::class, 'withdraw'])->name('tenant.admin.wallet.withdraw');
        
        Route::get('/plans', [\App\Http\Controllers\Tenant\Admin\AdminPlanController::class, 'index'])->name('tenant.admin.plans');
    });

    // ----- Student Panel -----
    Route::prefix('student')->middleware('student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('tenant.student.dashboard');
        Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('tenant.student.notifications');
        Route::patch('/notifications/{notification}/read', [StudentNotificationController::class, 'markRead'])->name('tenant.student.notifications.read');
        Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('tenant.student.assignments');
        Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('tenant.student.assignments.submit');
        
        Route::get('/courses', [\App\Http\Controllers\Tenant\Student\StudentCourseController::class, 'index'])->name('tenant.student.courses');
        Route::post('/courses/join-private', [\App\Http\Controllers\Tenant\Student\StudentCourseController::class, 'joinPrivate'])->name('tenant.student.courses.join-private');
        Route::post('/courses/{course}/enroll', [\App\Http\Controllers\Tenant\Student\StudentCourseController::class, 'enroll'])->name('tenant.student.courses.enroll');
    });
};

/*
|--------------------------------------------------------------------------
| SUPER-ADMIN — common routes (shared by both domain & localhost mode)
|--------------------------------------------------------------------------
*/
$superAdminRoutes = function () {
    Route::get('/login', [SuperAdminAuthController::class, 'showLogin'])->name('superadmin.login');
    Route::post('/login', [SuperAdminAuthController::class, 'login'])->name('superadmin.login.post');
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('superadmin.logout');

    Route::middleware('super_admin')->group(function () {
        Route::get('/', [SuperAdminDashboard::class, 'index'])->name('superadmin.dashboard');
        Route::get('/academics', [AcademicsController::class, 'index'])->name('superadmin.academics.index');
        Route::get('/academics/{tenant}', [AcademicsController::class, 'show'])->name('superadmin.academics.show');
        Route::patch('/academics/{tenant}/toggle', [AcademicsController::class, 'toggle'])->name('superadmin.academics.toggle');
        Route::patch('/academics/{tenant}/update-plan/{plan}', [AcademicsController::class, 'updatePlan'])->name('superadmin.academics.update-plan');
        Route::delete('/academics/{tenant}', [AcademicsController::class, 'destroy'])->name('superadmin.academics.destroy');
    });
};

/*
|--------------------------------------------------------------------------
| DEV MODE — localhost (no hosts file needed)
|   Landing:    http://localhost:8000/
|   SuperAdmin: http://localhost:8000/superadmin/login
|   Tenant:     http://localhost:8000/?tenant=demo  (any page works with ?tenant=)
|--------------------------------------------------------------------------
*/
if (in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1'])) {

    // Super-admin (prefixed with /superadmin)
    Route::prefix('superadmin')->group($superAdminRoutes);

    if (request()->has('tenant')) {
        // Tenant portal (triggered by ?tenant=subdomain, handled by IdentifyTenant middleware)
        Route::middleware('identify.tenant')->group($tenantRoutes);
    } else {
        // Landing page routes (only accessible if ?tenant= is NOT present)
        Route::get('/', [LandingController::class, 'index'])->name('landing.home');
        Route::get('/register-suite', [SuiteRegistrationController::class, 'create'])->name('landing.register');
        Route::post('/register-suite', [SuiteRegistrationController::class, 'store'])->name('landing.register.store');
        Route::get('/check-subdomain', [SuiteRegistrationController::class, 'checkSubdomain'])->name('landing.check-subdomain');
        Route::get('/success', [LandingController::class, 'success'])->name('landing.success');
    }

} else {

    /*
    |--------------------------------------------------------------------------
    | PRODUCTION MODE — matching host manually in middleware
    |--------------------------------------------------------------------------
    */
    $baseDomain = config('app.base_domain', 'acadsuite.local');

    // TIER 1: Landing page — e.g. acadsuite.com
    Route::domain($baseDomain)->group(function () {
        Route::get('/', [LandingController::class, 'index'])->name('landing.home');
        Route::get('/register-suite', [SuiteRegistrationController::class, 'create'])->name('landing.register');
        Route::post('/register-suite', [SuiteRegistrationController::class, 'store'])->name('landing.register.store');
        Route::get('/check-subdomain', [SuiteRegistrationController::class, 'checkSubdomain'])->name('landing.check-subdomain');
        Route::get('/success', [LandingController::class, 'success'])->name('landing.success');
    });

    // TIER 2: Pentagonware Admin — e.g. admin.acadsuite.com
    Route::domain('admin.' . $baseDomain)
        ->group($superAdminRoutes);

    // TIER 3 & 4: Tenant portals (Subdomains or Custom Domains)
    // We don't use {subdomain} parameter here to avoid "Missing required parameter" errors in route() helper.
    // The IdentifyTenant middleware will handle the logic by inspecting the request host.
    Route::middleware('identify.tenant')
        ->group($tenantRoutes);
}
