<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <title inertia><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="<?php echo e(mix('css/app.css')); ?>">



        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" >

        <!-- Scaling for very large screens -->
        <style>
            /*@media screen and (min-width: 1920px) {*/
            /*    body {*/
            /*        transform: scale(1.5);*/
            /*        transform-origin: top left; !* Adjust as needed *!*/
            /*    }*/
            /*}*/

            /* Optional: Scaling using root font size */
            @media screen and (min-width: 1024px) {
                html {
                    font-size: 24px; /* Increase the base font size */
                }
            }

            @media screen and (min-width: 2560px) {
                html {
                    font-size: 24px; /* Increase the base font size */
                }
            }
            @media screen and (min-width: 3840px) {
                html {
                    font-size: 32px; /* Increase the base font size */
                }
            }
        </style>


        <?php echo app('Tightenco\Ziggy\BladeRouteGenerator')->generate(); ?>

        <script src="<?php echo e(mix('js/manifest.js')); ?>" defer></script>
        <script src="<?php echo e(mix('js/vendor.js')); ?>" defer></script>
        <script src="<?php echo e(mix('js/app.js')); ?>" defer></script>




        <?php if (!isset($__inertiaSsr)) { $__inertiaSsr = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsr instanceof \Inertia\Ssr\Response) { echo $__inertiaSsr->head; } ?>
    </head>
    <body class="h-full font-sans antialiased bg-black">

        <?php if (!isset($__inertiaSsr)) { $__inertiaSsr = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsr instanceof \Inertia\Ssr\Response) { echo $__inertiaSsr->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>

    </body>
</html>
<?php /**PATH /var/www/html/resources/views/app.blade.php ENDPATH**/ ?>