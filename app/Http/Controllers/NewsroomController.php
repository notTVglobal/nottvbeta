<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\NewsStory;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Nette\Utils\DateTime;

class NewsroomController extends Controller
{
    public function index()
    {
        return Inertia::render('Newsroom/Index', [
            'news' => NewsStory::with('image')
                ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'news')
                ->withQueryString()
                ->through(fn($newsStory) => [
                    'id' => $newsStory->id,
                    'slug' => $newsStory->slug,
                    'title' => $newsStory->title,
                    'image' => $newsStory->image->name,
                    'created_at' => $newsStory->created_at,
                    'published_at' => $newsStory->published_at,
                    'can' => [
                        'editNewsStory' => Auth::user()->can('update', NewsStory::class),
                        'deleteNewsStory' => Auth::user()->can('delete', NewsStory::class),
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'createNewsStory' => Auth::user()->can('create', NewsStory::class),
                'editNewsStory' => Auth::user()->can('update', NewsStory::class),
                'publishNewsStory' => Auth::user()->can('publish', NewsStory::class)
            ]
        ]);
    }

    public function publish(HttpRequest $request)
    {
        $newsStory = NewsStory::find($request->id);
        $newsStory->published_at = date('Y-m-d H:i:s');
        $newsStory->save();

        return redirect()->route('newsroom')->with('message', 'News Story Published Successfully');
    }
}
