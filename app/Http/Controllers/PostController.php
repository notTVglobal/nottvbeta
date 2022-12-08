<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $posts = Post::all();
//
//        return Inertia::render(
//            'Posts/Index',
//            [
//                'posts' => $posts,
//            ]
//    );

                    return Inertia::render('News/Index', [
                        'news' => Post::query()
                            ->when(Request::input('search'), function ($query, $search) {
                                $query->where('title', 'like', "%{$search}%");
                            })
                            ->paginate(10)
                            ->withQueryString()
                            ->through(fn($post) => [
                                'id' => $post->id,
                                'title' => $post->title,
                                'slug' => $post->slug,
                                'can' => [
                                    'editPost' => Auth::user()->can('edit', Post::class),
                                ]
                            ]),
                        'filters' => Request::only(['search']),
                        'can' => [
                            'viewPost' => Auth::user()->can('view', Post::class),
                            'createPost' => Auth::user()->can('create', Post::class),
                            'editPost' => Auth::user()->can('edit', Post::class),
                            'viewCreator' => Auth::user()->can('viewCreator', User::class),
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
            'News/Create'
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
           'title' => 'required|string|max:255',
           'slug' => 'unique:posts|required|string|max:255',
           'content' => 'required',
        ]);
        Post::create([
           'title' => $request->title,
           'slug' => \Str::slug($request->slug),
           'content' => $request->content,
        ]);
        return redirect()->route('news')->with('message', 'News Post Created Successfully');

//        $attributes = Request::validate([
//            'title' => 'required|string|max:255',
//            'slug' => 'unique:posts|required|string|max:255',
//            'content' => 'required',
//        ]);
//
//        // create the post
//        Post::create($attributes);
//
//        sleep(1);
//        // redirect
//        return redirect('/posts')->with('message', 'Post Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::query()->where('slug', $slug)->firstOrFail();

        return Inertia::render(
            'News/{$id}/Index',
            [
                'news' => $post,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return Inertia::render(
            'News/{$id}/Edit',
        [
            'news' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $post->title = $request->title;
        $post->slug = \Str::slug($request->slug);
        $post->content = $request->content;
        $post->save();
        sleep(1);

        return redirect()->route('news')->with('message', 'News Post Updated Successfully');

//        // validate the request
//        $attributes = Request::validate([
//            'title' => 'required|string|max:255',
//            'slug' => 'required|string|max:255',
//            'content' => 'required',
//        ]);
//        // update the show
//        $post->update($attributes);
//        sleep(1);
//        // redirect
//        return Inertia::render('Posts/{$id}/Index', [
//            'post' => $post
//        ])->with('message', 'Post Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        sleep(1);

        return redirect()->route('news')->with('message', 'News Post Deleted Successfully');
    }
}
