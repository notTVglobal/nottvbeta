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
<h1>Verification email re-sent!</h1>
<p>Please check your email to confirm your account!</p>
<p>Check your junk mail folder as well!</p>

</body>
</html>
