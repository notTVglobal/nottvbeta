<?php $__env->startSection('title', 'Channel Restricted'); ?>

<?php $__env->startSection('message'); ?>
    <h1>Oops! This Channel is Restricted</h1>
    <?php if(isset($exception) && !empty($exception->getMessage())): ?>
        <p><?php echo e($exception->getMessage()); ?></p>
    <?php else: ?>
        <p>It seems you've stumbled upon a VIP area without the right pass. Don’t worry, there’s plenty more to explore and enjoy!</p>
    <?php endif; ?>
    <a href="<?php echo e(url('/')); ?>" class="btn btn-primary">Return to the Main Stage</a>
    <p>If you believe this is a mistake or need access, feel free to reach out. We're all about community here!</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/errors/403.blade.php ENDPATH**/ ?>