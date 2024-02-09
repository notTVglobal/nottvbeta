<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
</head>
<body>
<h1>You have new feedback from a user on notTV.</h1>
<h3>This form was sent from the orange feedback box.</h3>
<p>Name: {{ $name }}</p>
<p>Email: {{ $email }}</p>
<p>Phone: {{ $phone }}</p>
<p>City: {{ $city }}</p>
<p>Province: {{ $province }}</p>
<p>Screenshot: <a href="{{ $url }}">View Screenshot - not working right now</a></p>
<p>Message: {{ $message }}</p>

</body>
</html>
