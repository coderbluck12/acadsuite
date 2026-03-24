<?php $__env->startSection('content'); ?>
    <section class="hero" style="position: relative; background: url('<?php echo e(asset('assets/publications.jpg')); ?>') center/cover no-repeat; height: 75vh; display: flex; align-items: center; justify-content: center; color: white;">

        <!-- navbar on hero -->
        <div class="position-absolute container top-0 start-50 translate-middle-x px-3 pt-4 w-100 "
            style="z-index: 10;">
            <nav class="navbar navbar-expand-md border border-white rounded-5 glass-navbar px-4 py-2 w-100" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                <!-- Logo -->
                <a class="navbar-brand" href="<?php echo e(route('tenant.home', ['tenant' => $tenant->subdomain])); ?>">
                    <?php if($tenant->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $tenant->avatar)); ?>" alt="Logo" style="width: 100px;">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/logo.png')); ?>" alt="Logo" style="width: 100px;">
                    <?php endif; ?>
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Nav Items -->
                <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                    <ul class="navbar-nav gap-2" id="mainTab" role="tablist">
                        <li class="nav-item">
                            <a href="/" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="/publications" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Publications</a>
                        </li>
                        <li class="nav-item">
                            <a href="/resources" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Resources</a>
                        </li>
                        <li class="nav-item">
                            <a href="/blogs" class="nav-link active rounded-5" style="color: inherit; text-decoration: none; background-color: #dc3545; padding-inline: 20px;">Blogs</a>
                        </li>
                        <li class="nav-item">
                            <a href="/courses" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">Courses</a>
                        </li>
                        <?php if($tenant->orcid_url): ?>
                        <li class="nav-item">
                            <a href="<?php echo e($tenant->orcid_url); ?>" target="_blank" class="nav-link rounded-5" style="color: inherit; text-decoration: none;">ORCID</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/login" class="btn btn-primary rounded-pill px-3 py-1 ms-2">Login</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(253, 46, 46, 0.6);"></div>
        <div class="hero-content" style="position: relative; z-index: 2; text-align: center;">
            <h6 class="display-3 fw-bold ">Blogs</h6>
            <p class="fs-4">Home &gt; Blogs</p>
        </div>
    </section>

    <!-- Objectives of Blog -->
    <section class="container my-5 px-4">
        <h3 class="fw-semibold text-primary mb-3">Get educated with blogs</h3>
        <p class="text-muted fs-5">
            Explore our blog posts to stay informed on the latest developments in computer science and technology.
            Each article provides insightful analysis, practical tips, and thought-provoking ideas designed to support
            learning, research, and innovation for students, educators, and professionals alike.
        </p>
    </section>

    <section class="container my-5 px-4">
        <div class="row g-4 border rounded shadow-sm p-4 bg-white" style="border-top: 5px solid #dc3545 !important;">
            <?php $__empty_1 = true; $__currentLoopData = $blogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="blog-card border rounded-4 overflow-hidden shadow-sm h-100 d-flex flex-column" style="transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <?php if($blog->cover_image): ?>
                        <img src="<?php echo e(asset('storage/' . $blog->cover_image)); ?>" alt="<?php echo e($blog->title); ?>" class="blog-image w-100" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/studious_gril.jpg')); ?>" alt="<?php echo e($blog->title); ?>" class="blog-image w-100" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h5 class="fw-bold"><?php echo e($blog->title); ?></h5>
                        <p class="blog-meta text-muted mb-2">By <?php echo e($tenant->owner_name); ?> | <?php echo e($blog->created_at->format('M d, Y')); ?></p>
                        <p class="mb-3 flex-grow-1"><?php echo e(Str::limit($blog->excerpt ?? wp_strip_all_tags($blog->content), 120)); ?></p>
                        
                        <div class="mt-auto">
                            <a href="<?php echo e(route('tenant.blogs.show', ['tenant' => $tenant->subdomain, 'blog' => $blog->id])); ?>" class="btn btn-primary btn-sm rounded-pill px-4">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">No blog posts available right now.</p>
            </div>
            <?php endif; ?>
            
            <div class="col-12 mt-5 d-flex justify-content-center">
                <?php echo e($blogs->links('pagination::bootstrap-5') ?? ''); ?>

            </div>
        </div>
    </section>

    
    <footer class="bg-dark text-light pt-5 pb-4 mt-5">
        <div class="container px-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Have Questions?</h5>
                    <ul class="list-unstyled">
                        <?php if($tenant->address): ?><li class="mb-2"><i class="bi bi-geo-alt me-2"></i><?php echo e($tenant->address); ?></li><?php endif; ?>
                        <?php if($tenant->phone): ?><li class="mb-2"><i class="bi bi-telephone me-2"></i><?php echo e($tenant->phone); ?></li><?php endif; ?>
                        <?php if($tenant->email): ?><li><i class="bi bi-envelope me-2"></i><?php echo e($tenant->email); ?></li><?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('tenant.home', ['tenant' => $tenant->subdomain])); ?>" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="<?php echo e(route('tenant.publications', ['tenant' => $tenant->subdomain])); ?>" class="text-light text-decoration-none">Publications</a></li>
                        <li><a href="<?php echo e(route('tenant.blogs', ['tenant' => $tenant->subdomain])); ?>" class="text-light text-decoration-none">Blog</a></li>
                        <li><a href="<?php echo e(route('tenant.courses', ['tenant' => $tenant->subdomain])); ?>" class="text-light text-decoration-none">Courses</a></li>
                        <li><a href="<?php echo e(route('tenant.resources', ['tenant' => $tenant->subdomain])); ?>" class="text-light text-decoration-none">Resources</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <?php if(is_array($tenant->social_links) && count($tenant->social_links)): ?>
                    <h5 class="fw-bold mb-3">Connect With Us</h5>
                    <div>
                        <?php if(!empty($tenant->social_links['twitter'])): ?><a href="<?php echo e($tenant->social_links['twitter']); ?>" class="text-light me-3 fs-5" target="_blank"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                        <?php if(!empty($tenant->social_links['facebook'])): ?><a href="<?php echo e($tenant->social_links['facebook']); ?>" class="text-light me-3 fs-5" target="_blank"><i class="bi bi-facebook"></i></a><?php endif; ?>
                        <?php if(!empty($tenant->social_links['instagram'])): ?><a href="<?php echo e($tenant->social_links['instagram']); ?>" class="text-light fs-5" target="_blank"><i class="bi bi-instagram"></i></a><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-center mt-4 small">&copy; <?php echo e(date('Y')); ?> <?php echo e($tenant->name); ?>. All rights reserved.</div>
        </div>
    </footer>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/tenant/public/blogs.blade.php ENDPATH**/ ?>