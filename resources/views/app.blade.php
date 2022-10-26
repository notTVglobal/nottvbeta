<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @routes
{{--        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>--}}
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-black">

        @inertia

    </body>
</html>
