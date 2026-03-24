<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/logo.png')); ?>" />
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php echo $__env->yieldContent('body'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/layouts/app.blade.php ENDPATH**/ ?>