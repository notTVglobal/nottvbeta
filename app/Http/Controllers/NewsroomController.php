<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\NewsPost;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Nette\Utils\DateTime;

class NewsroomController extends Controller
{
    public function index()
    {
        return Inertia::render('Newsroom/Index', [
            'news' => NewsPost::with('image')
                ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($newsPost) => [
                    'id' => $newsPost->id,
                    'slug' => $newsPost->slug,
                    'title' => $newsPost->title,
                    'image' => $newsPost->image->name,
                    'created_at' => $newsPost->created_at,
                    'published_at' => $newsPost->published_at,
                    'can' => [
                        'editNewsPost' => Auth::user()->can('edit', NewsPost::class),
                        'deleteNewsPost' => Auth::user()->can('delete', NewsPost::class),
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'createNewsPost' => Auth::user()->can('create', NewsPost::class),
                'publishNewsPost' => Auth::user()->can('publish', NewsPost::class)
            ]
        ]);
    }

    public function publish(HttpRequest $request)
    {
        $newsPost = NewsPost::find($request->id);
        $newsPost->published_at = date('Y-m-d H:i:s');
        $newsPost->save();

        return redirect()->route('newsroom')->with('message', 'News Post Published Successfully');
    }
}
