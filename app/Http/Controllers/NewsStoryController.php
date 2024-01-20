<?php

namespace App\Http\Controllers;

use App\Models\NewsPerson;
use App\Models\NewsStory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsStoryController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('can:update,newsStory')->only(['edit']);
//        $this->middleware('can:create,newsStory')->only(['create']);
//        $this->middleware('can:delete,newsStory')->only(['destroy']);
//        $this->middleware('can:deleteNewsStory,newsStory')->only(['destroy']);
//        $this->authorizeResource(NewsStory::class, 'newsStory');

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('NewsStory/Index', [
            'news' => NewsStory::with('image')
                ->orderBy('published_at', 'desc')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->where('published_at', '!=', null)
                ->paginate(5, ['*'], 'news')
                ->withQueryString()
                ->through(fn($newsStory) => [
                    'slug' => $newsStory->slug,
                    'title' => html_entity_decode($newsStory->title),
                    'image' => $newsStory->image->name,
                    'published_at' => $newsStory->published_at,
                    'can' => [
                        'editNewsStory' => Auth::user()->can('update', NewsStory::class),
                        'deleteNewsStory' => Auth::user()->can('delete', NewsStory::class),
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
//                'editNewsStory' => Auth::user()->can('update', NewsStory::class),
                'createNewsStory' => Auth::user()->can('create', NewsStory::class),
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', NewsStory::class);
        return Inertia::render(
            'NewsStory/Create', [
                'can' => [
                    'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(HttpRequest $request)
    {

        $request->validate([
            'title' => 'unique:news_stories|required|string|max:255',
            'body' => 'required|string',
        ]);

        $newsStory = new NewsStory();
        $newsStory->title = htmlentities($request->title);
        $newsStory->content = htmlentities($request->body);
//        $newsStory->content_json = $request->content_json;
        $newsStory->slug = \Str::slug($request->title);
        $newsStory->user_id = Auth::user()->id;

        $newsStory->save();


//        NewsStory::create([
//            'title' => $request->title,
//            'slug' => \Str::slug($request->slug),
//            'content' => $request->content,
//        ]);
//        return redirect()->route('news.index')->with('message', 'News Story Created Successfully');

//        if (Auth::user()->can('viewAny', NewsPerson::class)){
//            return redirect()
//                ->route('newsroom')
//                ->with('message', 'News Story Created Successfully');
//        }
        return redirect()
            ->route('news/story.show',
                [$newsStory->slug])
            ->with('success', 'News Story Created Successfully');

    }


    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return Response
     */
    public function show($slug)
    {
        $newsStory = NewsStory::query()->where('slug', $slug)->firstOrFail();

      $canViewNewsroom = optional(Auth::user())->can('viewAny', NewsPerson::class);
      $canEditNewsStory = optional(Auth::user())->can('update', $newsStory);

      // Check if the story status is 6 and if the user lacks necessary permissions
      if ($newsStory->status->id == 6 && !($canViewNewsroom || $canEditNewsStory)) {
        throw new NotFoundHttpException('Story not available.');
      }

        return Inertia::render(
            'NewsStory/{$id}/Index',
            [
                'news' => [
                    'id' => $newsStory->id,
                    'slug' => $newsStory->slug,
                    'title' => html_entity_decode($newsStory->title),
                    'content' => html_entity_decode($newsStory->content),
//                    'content_json' => $newsStory->content_json,
                    'published_at' => $newsStory->published_at,
                    'updated_at' => $newsStory->updated_at,
                    'author' => $newsStory->user->name,
                ],
                'image' =>$newsStory->image->name,
                'can' => [
                    'editNewsStory' => optional(Auth::user())->can('update', $newsStory) ?: false,
                    'deleteNewsStory' => optional(Auth::user())->can('delete', $newsStory) ?: false,
                    'viewNewsroom' => optional(Auth::user())->can('viewAny', NewsPerson::class) ?: false
                ]
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  NewsStory $newsStory
     * @return Response
     */
    public function edit($slug, NewsStory $newsStory)
    {
        $this->authorize('update', $newsStory);

        $post = NewsStory::query()->where('slug', $slug)->firstOrFail();
        return Inertia::render(
            'NewsStory/{$id}/Edit',
            [
                'news' => [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => html_entity_decode($post->title),
                    'content' => html_entity_decode($post->content),
//                    'content_json' => $post->content_json,
                ],
                'can' => [
                    'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
                ]
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HttpRequest $request
     * @param  NewsStory $newsStory
     * @return RedirectResponse
     */
    public function update(HttpRequest $request, NewsStory $newsStory)
    {
        $this->authorize('update', $newsStory);

        $newsStory = NewsStory::find($request->id);

        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('news_stories')->ignore($newsStory->id)],
            'body' => 'required|string',
//            'body' => 'required|array',
        ]);

        $newsStory->title = $request->title;
        $newsStory->content = $request->body;
        // tec21: I don't know how to display the content of the array in html
        // we'll just keep this here for now.
//        $newsStory->content_json = $request->body;
        $newsStory->slug = \Str::slug($request->title);
        $newsStory->save();
        sleep(1);

//        if (Auth::user()->can('viewAny', NewsPerson::class)){
//            return redirect()
//                ->route('newsroom')
//                ->with('message', 'News Story Updated Successfully');
//        }
        return redirect()
            ->route('news/story.show',
                [$newsStory->slug])
            ->with('success', 'News Story Updated Successfully');

    }

    // Content is saved through this method, it uses Tiptap
    // on the front end to save the file as the user types.
    // The title is saved through the update method.
    public function save(HttpRequest $request)
    {
        $newsStory = NewsStory::find($request->id);

        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('news_stories')->ignore($newsStory->id)],
            'body' => 'required|string',
//            'content_json' => 'required|array',
        ]);

        $id = $request->id;
        $content = htmlentities($request->body);
//        $content = $request->content_json;

        $newsStory = NewsStory::find($id);
        $newsStory->content_json = $request->content_json;
        $newsStory->content = $content;
        $newsStory->save();

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', NewsStory::class);
        NewsStory::destroy($id);
        sleep(1);

        return redirect()->route('newsroom')->with('message', 'News Story Deleted Successfully');
    }

}
