<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" >

        <!-- Scaling for very large screens -->
        <style>
            /*@media screen and (min-width: 1920px) {*/
            /*    body {*/
            /*        transform: scale(1.5);*/
            /*        transform-origin: top left; !* Adjust as needed *!*/
            /*    }*/
            /*}*/

            /* Optional: Scaling using root font size */
            @media screen and (min-width: 1920px) {
                html {
                    font-size: 18px; /* Increase the base font size */
                }
            }
            @media screen and (min-width: 2560px) {
                html {
                    font-size: 24px; /* Increase the base font size */
                }
            }
            @media screen and (min-width: 3840px) {
                html {
                    font-size: 32px; /* Increase the base font size */
                }
            }
        </style>


        @routes

        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
{{--        <script nonce="{{ csp_nonce() }}"></script>--}}


{{--        <script type="text/javascript" src="js/emojionearea.min.js"></script>--}}
        @inertiaHead
    </head>
    <body class="h-full font-sans antialiased bg-black">

        @inertia

    </body>
</html>
