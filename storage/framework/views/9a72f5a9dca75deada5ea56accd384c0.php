<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <?php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->courses()->count() >= 5;
    ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Courses 
            <?php if($isLimitReached): ?>
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            <?php endif; ?>
        </h4>
        <button class="btn btn-primary rounded-pill px-4" <?php echo e($isLimitReached ? 'disabled' : ''); ?> data-bs-toggle="modal" data-bs-target="#createCourseModal">
            <i class="bi bi-plus-circle me-1"></i> Add Course
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="courseTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Level</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($courses->firstItem() + $i); ?></td>
                        <td><?php echo e(Str::limit($course->title, 60)); ?></td>
                        <td><?php echo e($course->level ?: '—'); ?></td>
                        <td><span class="badge <?php echo e($course->is_published ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($course->is_published ? 'Published' : 'Draft'); ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editCourseModal<?php echo e($course->id); ?>"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="<?php echo e(route('tenant.admin.courses.destroy', ['tenant' => $tenant->subdomain, 'course' => $course])); ?>" class="d-inline" onsubmit="return confirm('Delete this course?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($courses->links()); ?>

        </div>
    </div>

    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal<?php echo e($course->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="<?php echo e(route('tenant.admin.courses.update', ['tenant' => $tenant->subdomain, 'course' => $course])); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($course->title); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo e($course->description); ?></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Level</label>
                                <input type="text" name="level" class="form-control" value="<?php echo e($course->level); ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Duration</label>
                                <input type="text" name="duration" class="form-control" value="<?php echo e($course->duration); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cover Image</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Visibility</label>
                            <select name="is_published" class="form-select w-100" required>
                                <option value="1" <?php echo e($course->is_published ? 'selected' : ''); ?>>Published</option>
                                <option value="0" <?php echo e(!$course->is_published ? 'selected' : ''); ?>>Draft</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Create Course Modal -->
<div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createCourseModalLabel"><i class="bi bi-book me-2"></i> Create New Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('tenant.admin.courses.store', ['tenant' => $tenant->subdomain])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="courseTitle" class="form-label fw-semibold">Course Title</label>
                        <input type="text" name="title" class="form-control" id="courseTitle" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label for="courseDesc" class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" id="courseDesc" placeholder="Description" rows="3"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="courseLevel" class="form-label fw-semibold">Level</label>
                            <input type="text" name="level" class="form-control" id="courseLevel" placeholder="e.g. Beginner">
                        </div>
                        <div class="col-6">
                            <label for="courseDuration" class="form-label fw-semibold">Duration</label>
                            <input type="text" name="duration" class="form-control" id="courseDuration" placeholder="e.g. 4 Weeks">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="courseMaterials" class="form-label fw-semibold">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control" id="courseMaterials" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="visibility" class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" id="visibility" class="form-select w-100" required>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload"></i> Create Course</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>$(document).ready(function(){ $('#courseTable').DataTable({ destroy: true, ordering: true }); });</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/tenant/admin/courses.blade.php ENDPATH**/ ?>