<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia><?php echo e(config('app.name', 'Laravel')); ?> Chat</title>


</head>
<body class="font-sans antialiased bg-black">
<?php if (!isset($__inertiaSsr)) { $__inertiaSsr = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsr instanceof \Inertia\Ssr\Response) { echo $__inertiaSsr->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
<div class="container">
    <div class="card">
        <div class="card-header">Conversation</div>
        <div class="card-body">
            <chat-messages :messages="messages"></chat-messages>
        </div>
        <div class="card-footer">
            <chat-form v-on:messagesent="addMessage" :user="<?php echo e(Auth::user()); ?>"></chat-form>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH /var/www/nottvbeta/resources/views/chat.blade.php ENDPATH**/ ?>