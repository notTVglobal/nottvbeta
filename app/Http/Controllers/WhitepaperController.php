<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class WhitepaperController extends Controller
{
    /**
     * Find the path to a localized Markdown resource.
     *
     * @param string $name
     * @return string|null
     */
    public static function localizedMarkdownPath(string $name): ?string
    {
        $localName = preg_replace('#(\.md)$#i', '.'.app()->getLocale().'$1', $name);

        return Arr::first([
            resource_path('markdown/'.$localName),
            resource_path('markdown/'.$name),
            resource_path('views/markdown/'.$name),
        ], function ($path) {
            return file_exists($path);
        });
    }
    /**
     * Show the whitepaper for the application.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $whitepaperFile = self::localizedMarkdownPath('whitepaper.md');

        return Inertia::render('Whitepaper', [
            'whitepaper' => Str::markdown(file_get_contents($whitepaperFile)),
        ]);
    }
}
