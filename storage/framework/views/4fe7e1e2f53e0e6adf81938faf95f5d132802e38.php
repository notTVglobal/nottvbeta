<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
</head>
<body>
<h1>You have new feedback from a user on notTV.</h1>
<h3>This form was sent from the orange feedback box.</h3>
<p>Name: <?php echo e($name); ?></p>
<p>Email: <?php echo e($email); ?></p>
<p>Phone: <?php echo e($phone); ?></p>
<p>City: <?php echo e($city); ?></p>
<p>Province: <?php echo e($province); ?></p>
<p>Screenshot: <a href="<?php echo e($url); ?>">View Screenshot - not working right now</a></p>
<p>Message: <?php echo e($message); ?></p>

</body>
</html>
<?php /**PATH /var/www/html/resources/views/mail/feedback.blade.php ENDPATH**/ ?>