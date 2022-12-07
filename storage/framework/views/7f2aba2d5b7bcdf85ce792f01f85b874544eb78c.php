<?php use Jenssegers\Agent\Agent; ?>
    <!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
    <h2>This is mobile device</h2>

    <?php if((new Agent())->isDesktop()): ?>
        <h2><?php echo e($result); ?></h2>
    <?php elseif((new Agent())->isMobile()): ?>
        <h2><?php echo e($result); ?></h2>
    <?php else: ?>
        <h2><?php echo e($result); ?></h2>
    <?php endif; ?>
</div>
</body>
</html>
<?php /**PATH /var/www/nottvbeta/resources/views/devices/mobile.blade.php ENDPATH**/ ?>