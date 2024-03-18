<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - The notTV Team</title>
    <style>
        body { text-align: center; padding: 50px; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
        .logo { margin-bottom: 40px; }
        .message { margin-top: 20px; }
    </style>
</head>
<body>
<div class="content">
    <div class="logo">

        <img src="https://not.tv/storage/images/logo_black_311.png" class="w-full" alt="notTV Logo">

    </div>
    <div class="message">
        <?php echo $__env->yieldContent('message'); ?>
    </div>
</div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/errors/layout.blade.php ENDPATH**/ ?>