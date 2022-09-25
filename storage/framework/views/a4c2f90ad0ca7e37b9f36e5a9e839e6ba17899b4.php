<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="<?php echo e(csrf_token()); ?>">

    <title inertia><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->

    <!-- Styles -->

</head>
<body>
<p>Please check your email to confirm your account! <br />(check your junk mail folder too!)</p>
<form action="/email/verification-notification" method="POST">
    <?php echo csrf_field(); ?>
    <button type="submit" value="Submit">Re-send verification</button>
</form>
<p>If you need help, please send us a message <a href="https://help.not.tv/">here</a>.</p>
</body>
</html>
<?php /**PATH /var/www/nottvbeta/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>