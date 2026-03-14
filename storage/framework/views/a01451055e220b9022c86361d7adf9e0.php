

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <div class="card shadow profile-card p-4">
        <h3 class="text-center mb-4">Profile Management</h3>
        <form method="POST" action="<?php echo e(route('tenant.admin.profile.update', ['tenant' => $tenant->subdomain])); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="text-center mb-3">
                <?php if($tenant->avatar): ?>
                    <img src="<?php echo e(asset('storage/' . $tenant->avatar)); ?>" alt="Profile" class="rounded-circle border" style="width:120px;height:120px;object-fit:cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center border" style="width:120px;height:120px;">
                        <span class="text-white fw-bold" style="font-size:3rem;"><?php echo e(strtoupper(substr($tenant->owner_name,0,1))); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Change Profile Picture</label>
                <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Your Full Name</label>
                    <input type="text" name="owner_name" class="form-control" value="<?php echo e(old('owner_name', $tenant->owner_name)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Suite Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $tenant->name)); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Bio</label>
                <textarea name="bio" class="form-control" rows="4"><?php echo e(old('bio', $tenant->bio)); ?></textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" value="<?php echo e($tenant->email); ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="tel" name="phone" class="form-control" value="<?php echo e(old('phone', $tenant->phone)); ?>">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ORCID Link</label>
                    <input type="url" name="orcid_url" class="form-control" value="<?php echo e(old('orcid_url', $tenant->orcid_url)); ?>" placeholder="https://orcid.org/0000-0000-0000-0000">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $tenant->address)); ?>">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                     <label class="form-label fw-semibold">Custom Domain</label>
                     <input type="text" name="custom_domain" class="form-control" value="<?php echo e(old('custom_domain', $tenant->custom_domain)); ?>" placeholder="e.g. platform.myschool.com">
                     <div class="form-text">Point your domain's CNAME or A record to our server's IP address.</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Update Profile</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/admin/profile.blade.php ENDPATH**/ ?>