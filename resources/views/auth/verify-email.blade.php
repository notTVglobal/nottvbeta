<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->

    <!-- Styles -->

</head>
<body>
<p>Please check your email to confirm your account!</p>
<p>Check your junk mail folder as well!</p>
<form action="/email/verification-notification" method="POST">
    @csrf
    <button type="submit" value="Submit">Re-send verification</button>
</form>

</body>
</html>
