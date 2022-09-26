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


<body bgcolor="#d3d3d3">
<div style="margin-left:20px; margin-top:20px; text-align: center; position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);">
    <img style="margin-bottom:38px;" src="../storage/images/logo_black_311.png">
    <div style="margin-bottom:38px; font-size:large; line-height:1.5; font-family: Arial, Helvetica, sans-serif;">

        <h1 style="font:bold;">Verification email re-sent!</h1>

        <p>Please check your email to confirm your account.<br />
            <span style="font-size:small; font-style:italic;">Check your junk mail folder as well</span></p>

    </div>
</div>

</body>
</html>
