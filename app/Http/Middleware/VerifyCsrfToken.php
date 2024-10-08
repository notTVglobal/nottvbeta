<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
//        '/upload',
//        '/showEpisodesUploadPoster',
//        '/showsUploadPoster',
//        '/teamsUploadLogo',
//        '/moviesUploadPoster',
        '/api/image-upload',
        '/chat/message',
        '/api/message',
        '/mistTrigger',
        '/videoupload',
        '/news/save',
        '/broadcasting/auth',
        'logout',
        'stripe/*',
        'notifications/*',
        'notifications',
        '^shows\/[^\/]+\/episode\/[^\/]+$',
//        '/subscribe',
    ];
}
