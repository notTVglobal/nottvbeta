<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
    @if((new \Jenssegers\Agent\Agent())->isDesktop())
        <h2>{{$result}}</h2>
    @elseif((new \Jenssegers\Agent\Agent())->isMobile())
        <h2>{{$result}}</h2>
    @else
        <h2>{{$result}}</h2>
    @endif
</div>
</body>
</html>
