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
<p>Please check your email to confirm your account! <br />(check your junk mail folder too!)</p>
<form action="/email/verification-notification" method="POST">
    @csrf
    <button type="submit" value="Submit">Re-send verification</button>
</form>
<p>If you need help, please send us a message <a href="https://help.not.tv/">here</a>.</p>
</body>
</html>
