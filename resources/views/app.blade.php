<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @inertiaHead
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/storage/images/apple-touch-icon-180x180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="/storage/images/apple-touch-icon-167x167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/storage/images/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/storage/images/apple-touch-icon-120x120.png">

        <!-- App Card Properties -->
{{--        TODO: Create the Apps --}}
{{--        <meta name="twitter:app:country" content="US">--}}
{{--        <meta name="twitter:app:name:iphone" content="Your iPhone App Name">--}}
{{--        <meta name="twitter:app:id:iphone" content="your_app_id">--}}
{{--        <meta name="twitter:app:url:iphone" content="yourapp://page">--}}
{{--        <meta name="twitter:app:name:googleplay" content="Your Android App Name">--}}
{{--        <meta name="twitter:app:id:googleplay" content="your_app_id">--}}
{{--        <meta name="twitter:app:url:googleplay" content="yourapp://page">--}}

        <!-- Player Card Properties -->
{{--        TODO: Create our Bonus Content as shareable videos and Shareable pages. --}}
{{--        <meta name="twitter:player" content="https://example.com/iframe_player">--}}
{{--        <meta name="twitter:player:width" content="1280">--}}
{{--        <meta name="twitter:player:height" content="720">--}}
{{--        <meta name="twitter:player:stream" content="https://example.com/video.mp4">--}}
{{--        <meta name="twitter:player:stream:content_type" content="video/mp4">--}}






{{--        <meta property="csp-nonce" content="{{ csp_nonce() }}">--}}

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/liveStreamGuide.css') }}">
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


            @media screen and (min-width: 1024px) {
                html {
                    font-size: 16px; /* Increase the base font size */
                }
            }


            /*@media screen and (min-width: 1024px) {*/
            /*    html {*/
            /*        font-size: 12px; !* Increase the base font size *!*/
            /*    }*/
            /*}*/

            /*@media screen and (min-width: 1600px) {*/
            /*    html {*/
            /*        font-size: 14px; !* Increase the base font size *!*/
            /*    }*/
            /*}*/

            @media screen and (min-width: 1920px) {
                html {
                    font-size: 16px; /* Increase the base font size */
                }
            }

            @media screen and (min-width: 2560px) {
                html {
                    font-size: 20px; /* Increase the base font size */
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

    </head>
    <body class="h-full font-sans antialiased bg-black">

        @inertia

    </body>
</html>
