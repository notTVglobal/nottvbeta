

<?php $__env->startSection('title', 'Access Forbidden'); ?>

<?php $__env->startSection('message'); ?>
    <h1>Access Forbidden</h1>
    <p>You do not have the necessary permissions to access this page.</p>
    <a href="<?php echo e(url('/')); ?>">Go to Homepage</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/errors/403.blade.php ENDPATH**/ ?>