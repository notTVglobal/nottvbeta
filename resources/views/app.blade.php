<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
{{--        <meta property="csp-nonce" content="{{ csp_nonce() }}">--}}

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
{{--        <style nonce="{{ csp_nonce() }}"></style>--}}
{{--        <link rel="stylesheet" href="css/emojionearea.min.css">--}}

        <!-- Favicon -->
        < link rel="shortcut icon" href="{{ asset('favicon.ico') }}" >


        @routes

        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
{{--        <script nonce="{{ csp_nonce() }}"></script>--}}


{{--        <script type="text/javascript" src="js/emojionearea.min.js"></script>--}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-black">

        @inertia

    </body>
</html>
