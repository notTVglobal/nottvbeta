

<?php $__env->startSection('title', 'Page Not Found'); ?>

<?php $__env->startSection('message'); ?>
    <h1>Page Not Found</h1>
    <p>Sorry, but the page you were trying to view does not exist.</p>
    <a href="<?php echo e(url('/')); ?>">Go to Homepage</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/errors/404.blade.php ENDPATH**/ ?>