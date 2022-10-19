<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title inertia><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="<?php echo e(mix('css/app.css')); ?>">

        <?php echo app('Tightenco\Ziggy\BladeRouteGenerator')->generate(); ?>
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="<?php echo e(mix('js/manifest.js')); ?>" defer></script>
        <script src="<?php echo e(mix('js/vendor.js')); ?>" defer></script>
        <script src="<?php echo e(mix('js/app.js')); ?>" defer></script>
        <?php if (!isset($__inertiaSsr)) { $__inertiaSsr = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsr instanceof \Inertia\Ssr\Response) { echo $__inertiaSsr->head; } ?>
    </head>
    <body class="font-sans antialiased bg-black">

        <?php if (!isset($__inertiaSsr)) { $__inertiaSsr = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsr instanceof \Inertia\Ssr\Response) { echo $__inertiaSsr->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>

    </body>
</html>
<?php /**PATH /var/www/nottvbeta/resources/views/app.blade.php ENDPATH**/ ?>