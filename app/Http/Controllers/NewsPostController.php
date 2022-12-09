<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class NewsPostController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('can:editNewsPost,newsPost')->only(['update']);
//        $this->middleware('can:deleteNewsPost,newsPost')->only(['destroy']);
//        $this->authorizeResource(NewsPost::class, 'newsPost');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('News/Index', [
            'news' => NewsPost::with('image')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->where('published_at', '!=', null)
                ->paginate(10)
                ->withQueryString()
                ->through(fn($newsPost) => [
                    'slug' => $newsPost->slug,
                    'title' => $newsPost->title,
                    'image' =>$newsPost->image->name,
                    'can' => [
                        'editNewsPost' => Auth::user()->can('edit', NewsPost::class),
                        'deleteNewsPost' => Auth::user()->can('delete', NewsPost::class),
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'createNewsPost' => Auth::user()->can('create', NewsPost::class),
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render(
            'News/Create', [
                'can' => [
                    'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'title' => 'unique:news_posts|required|string|max:255',
            'content' => 'required',
        ]);

        $newsPost = new NewsPost();
        $newsPost->title = $request->title;
        $newsPost->content = $request->content;
        $newsPost->slug = \Str::slug($request->title);
        $newsPost->user_id = Auth::user()->id;

        $newsPost->save();


//        NewsPost::create([
//            'title' => $request->title,
//            'slug' => \Str::slug($request->slug),
//            'content' => $request->content,
//        ]);
//        return redirect()->route('news')->with('message', 'News Post Created Successfully');

//        if (Auth::user()->can('viewAny', NewsPerson::class)){
//            return redirect()
//                ->route('newsroom')
//                ->with('message', 'News Post Created Successfully');
//        }
        return redirect()
            ->route('news.show',
                [$newsPost->slug])
            ->with('message', 'News Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  NewsPost $newsPost
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $newsPost = NewsPost::query()->where('slug', $slug)->firstOrFail();

        return Inertia::render(
            'News/{$id}/Index',
            [
                'news' => $newsPost,
                'image' =>$newsPost->image->name,
                'can' => [
                    'editNewsPost' => Auth::user()->can('edit', NewsPost::class),
                    'deleteNewsPost' => Auth::user()->can('delete', NewsPost::class),
                    'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
                ]
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  NewsPost $newsPost
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, NewsPost $newsPost)
    {
        $this->authorize('edit', $newsPost);
        $post = NewsPost::query()->where('slug', $slug)->firstOrFail();
        return Inertia::render(
            'News/{$id}/Edit',
            [
                'news' => $post,
                'can' => [
                    'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
                ]
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  NewsPost $newsPost
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, NewsPost $newsPost)
    {

        $newsPost = NewsPost::find($request->id);
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('news_posts')->ignore($newsPost->id)],
            'content' => 'required',
        ]);

        $newsPost->title = $request->title;
        $newsPost->slug = \Str::slug($request->title);
        $newsPost->content = $request->content;
        $newsPost->save();
        sleep(1);

//        if (Auth::user()->can('viewAny', NewsPerson::class)){
//            return redirect()
//                ->route('newsroom')
//                ->with('message', 'News Post Updated Successfully');
//        }
        return redirect()
            ->route('news.show',
                [$newsPost->slug])
            ->with('message', 'News Post Updated Successfully');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  NewsPost $newsPost
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        NewsPost::destroy($id);
        sleep(1);

        return redirect()->route('news')->with('message', 'News Post Deleted Successfully');
    }

}
