<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class WhitepaperController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $whitepaperFile = Jetstream::localizedMarkdownPath('whitepaper.md');

        return Inertia::render('Whitepaper', [
            'whitepaper' => Str::markdown(file_get_contents($whitepaperFile)),
        ]);
    }
}
