<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
</head>
<body>
<h1>You have a new message from the notTV Contact Us form.</h1>
<h3>This message was sent from the contact us form on the public not.tv/contact page.</h3>
<p>Name: <?php echo e($name); ?></p>
<p>Email: <?php echo e($email); ?></p>
<p>Phone: <?php echo e($phone); ?></p>
<p>Message: <?php echo e($message); ?></p>

</body>
</html>
<?php /**PATH /var/www/html/resources/views/mail/contactUs.blade.php ENDPATH**/ ?>