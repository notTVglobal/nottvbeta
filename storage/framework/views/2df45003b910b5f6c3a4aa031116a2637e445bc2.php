<!-- resources/views/chat.blade.php -->


<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia><?php echo e(config('app.name', 'Laravel')); ?> Chat</title>

</head>
<body class="font-sans antialiased bg-black">

<div class="container">
    <div class="card">
        <div class="card-header">Conversation 77</div>
        <div class="card-body">
            <chat-container></chat-container>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH /var/www/nottvbeta/resources/views/chat.blade.php ENDPATH**/ ?>