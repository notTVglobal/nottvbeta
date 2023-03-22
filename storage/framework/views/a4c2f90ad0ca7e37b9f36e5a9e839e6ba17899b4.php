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
<body bgcolor="#d3d3d3">
<div style="margin-left:20px; margin-top:20px; text-align: center; position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);">
    <img style="margin-bottom:38px;" src="../storage/images/logo_black_311.png">
    <div style="margin-bottom:38px; font-size:large; line-height:1.5; font-family: Arial, Helvetica, sans-serif;">

        !!!!!Before continuing, will you please verify your email address by clicking on the link we just emailed to you?<br />
        If you didn't receive the email, we will gladly send you another.

        <form action="/email/verification-notification" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" value="Submit" style="margin-top:10px; padding:10px; font-size:large; cursor:pointer;">Re-send verification</button>
        </form>

        <p style="font-size:large;">If you need help, please send us a message <a href="https://help.not.tv/">here</a>.</p>

    </div>
</div>

</body>
</html>
<?php /**PATH /var/www/nottvbeta/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>