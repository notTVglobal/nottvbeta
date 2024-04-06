<?php $__env->startSection('title', 'Whoops! Lost in Transmission?'); ?>

<?php $__env->startSection('message'); ?>
    <h1>Seems Like a Dead End!</h1>
    <p>Oops! Looks like the content you were searching for is playing hide-and-seek. It might have moved or doesn't exist anymore. But don't worry, we've got plenty of other exciting channels to explore!</p>
    <a href="<?php echo e(url('/')); ?>" class="btn btn-primary">Back to the Main Screen</a>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/errors/404.blade.php ENDPATH**/ ?>