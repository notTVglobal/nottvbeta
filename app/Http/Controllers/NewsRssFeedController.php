<?php

namespace App\Http\Controllers;

use App\Models\NewsPerson;
use App\Models\NewsPost;
use App\Models\NewsRssFeed;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NewsRssFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('News/Rss/Index', [
            'feeds' => NewsRssFeed::query()
                ->orderBy('name', 'asc')
                ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10, ['*'], 'news')
                ->withQueryString()
                ->through(fn($newsRssFeed) => [
                    'id' => $newsRssFeed->id,
                    'slug' => $newsRssFeed->slug,
                    'name' => html_entity_decode($newsRssFeed->name),
                    'url' => $newsRssFeed->url
                ]),
            'filters' => Request::only(['search']),
            'can' => [
//                'editNewsPost' => Auth::user()->can('update', NewsPost::class),
                'createNewsPost' => Auth::user()->can('create', NewsPost::class),
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $this->authorize('create', NewsPost::class);
        return Inertia::render(
            'News/Rss/Create', [
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'name' => 'unique:news_rss_feeds|required|string|max:255',
            'url' => 'required|active_url',
        ]);

        $newsRssFeed = new NewsRssFeed();
        $newsRssFeed->name = $request->name;
        $newsRssFeed->slug = \Str::slug($request->name);
        $newsRssFeed->url = $request->url;

        $newsRssFeed->save();

        return redirect()
            ->route('feeds.show',
                [$newsRssFeed->slug])
            ->with('message', 'RSS Feed Added Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show($slug)
    {
        $newsRssFeed = NewsRssFeed::query()->where('slug', $slug)->firstOrFail();
//        $feed = file_get_contents($newsRssFeed->url);
        $data = fopen($newsRssFeed->url, 'r');
        $feed = stream_get_contents($data);
        $xml = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);

//        foreach ($inputs as $input) {
//            $dataArray[] = [
//                'title' => $input->channel->item[$input]->title,
//                'author' => $input->channel->item[$input]->author,
//                'category' => $input->channel->item[$input]->category,
//                'link' => $input->channel->item[$input]->link,
//                'description' => $input->channel->item[$input]->description,
//                'pubDate' => $input->channel->item[$input]->pubDate,
//            ];
//
//            dd($dataArray);


//            echo "\nThe Title: " . $title . ".\n";
//            echo "\nThe Author: " . $author . ".\n";
//            echo "\nThe Category: " . $category . ".\n";
//            echo "\nChannel Link: " . $link . ".\n";
//            echo "\nChannel Description: " . $description . ".\n";
//            echo "\nDate of Publication: " . $pubDate . ".\n";
//        }


//        $feed = htmlspecialchars_decode($feed);
//        dd($feed);

//        dd($xml);
        collect($xml);
        $json = json_encode($xml->channel);
        $array = json_decode($json);
        return Inertia::render(
            'News/Rss/{$id}/Index',
            [
                'feed' => [
                    'id' => $newsRssFeed->id,
                    'slug' => $newsRssFeed->slug,
                    'name' => html_entity_decode($newsRssFeed->name),
                    'url' => html_entity_decode($newsRssFeed->url),
                    'items' => $array
                ],
                'can' => [
                    'editNewsPost' => Auth::user()->can('update', NewsPost::class),
                    'deleteNewsPost' => Auth::user()->can('delete', NewsPost::class),
                    'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
                ]
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', NewsPost::class);
        NewsRssFeed::destroy($id);

        return redirect()->route('feeds.index')->with('message', 'RSS Feed Deleted Successfully');
    }
}
