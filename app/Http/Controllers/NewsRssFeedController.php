<?php

namespace App\Http\Controllers;

use App\Jobs\FetchRssFeedItemsJob;
use App\Models\NewsPerson;
use App\Models\NewsRssFeedItemArchive;
use App\Models\NewsRssFeedItemTemp;
use App\Models\NewsStory;
use App\Models\NewsRssFeed;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NewsRssFeedController extends Controller {

  public function __construct() {

    $this->middleware('can:viewAny,App\Models\NewsRssFeed')->only(['index']);
    $this->middleware('can:view,App\Models\NewsRssFeed')->only(['show']);
    $this->middleware('can:create,App\Models\NewsRssFeed')->only(['create']);
    $this->middleware('can:update,newsRssFeed')->only(['update']);
    $this->middleware('can:delete,App\Models\NewsRssFeed')->only(['destroy']);

  }


  public function index(): \Inertia\Response {
    return Inertia::render('NewsRssFeeds/Index', [
        'feeds'   => NewsRssFeed::query()
            ->orderBy('last_successful_update', 'desc')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10, ['*'], 'rss')
            ->withQueryString()
            ->through(fn($newsRssFeed) => [
                'id'                   => $newsRssFeed->id,
                'slug'                 => $newsRssFeed->slug,
                'name'                 => $newsRssFeed->name,
                'url'                  => $newsRssFeed->url,
                'lastSuccessfulUpdate' => $newsRssFeed->last_successful_update,
            ]),
        'filters' => Request::only(['search']),
        'can'     => [
//                'editNewsStory' => Auth::user()->can('update', NewsStory::class),
            'createNewsStory' => Auth::user()->can('create', NewsStory::class),
            'viewNewsroom'    => Auth::user()->can('viewAny', NewsPerson::class)
        ]
    ]);
  }

  public function create(): \Inertia\Response {
    return Inertia::render(
        'NewsRssFeeds/Create', [
            'can' => [
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]
    );
  }

  public function store(HttpRequest $request): \Illuminate\Http\RedirectResponse {
    Log::info('RSS Feed: ' . $request->name . '.');
    try {
      $request->validate([
          'name' => 'unique:news_rss_feeds|required|string|max:255',
          'url'  => 'required',
      ]);

      $newsRssFeed = new NewsRssFeed();
      $newsRssFeed->name = $request->name;
      $newsRssFeed->slug = \Str::slug($request->name);
      $newsRssFeed->url = $request->url;

      $newsRssFeed->save();

      FetchRssFeedItemsJob::dispatch($newsRssFeed);

      return redirect()
          ->route('newsRssFeeds.index',
              [$newsRssFeed->slug])
          ->with('message', 'RSS Feed Added Successfully');
    } catch (\Exception $e) {
      Log::error('Error in storing RSS Feed: ' . $e->getMessage());

      return redirect()
          ->route('newsRssFeeds.index')
          ->with('error', 'Error adding RSS Feed: ' . $e->getMessage());
    }
  }

  public function show(NewsRssFeed $newsRssFeed): \Inertia\Response {
    // Depending on the size of your data, using union and groupBy might impact performance.
    // If performance becomes an issue, consider optimizing your database indexes or rethinking the data architecture.
    //      $tempItems = NewsRssFeedItemTemp::select('title', 'description', 'link as url', 'pubDate')
    //          ->where('news_rss_feed_id', $newsRssFeed->id);
    // Combine temp and archive items, then apply filters and paginate
    $feedItems = DB::table(DB::raw("((SELECT id, title, description, link as url, image_url, pubDate, is_saved, NULL as image_id FROM news_rss_feed_item_temps WHERE news_rss_feed_id = {$newsRssFeed->id})
                                 UNION
                                 (SELECT id, title, description, link as url, image_url, pubDate, 1 as is_saved, image_id FROM news_rss_feed_item_archives WHERE news_rss_feed_id = {$newsRssFeed->id})) as feed_items"))
        ->distinct('url')
        ->select('id', 'title', 'description', 'url', 'image_url', 'pubDate', 'is_saved', 'image_id')
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        })
        ->latest('pubDate')
        ->paginate(10);

    return Inertia::render(
        'NewsRssFeeds/{$id}/Index',
        [
            'feed'    => [
                'id'                   => $newsRssFeed->id,
                'slug'                 => $newsRssFeed->slug,
                'name'                 => html_entity_decode($newsRssFeed->name),
                'url'                  => html_entity_decode($newsRssFeed->url),
                'lastSuccessfulUpdate' => $newsRssFeed->last_successful_update,
                'items'                => $feedItems
            ],
            'can'     => [
                'edit'   => Auth::user()->can('update', NewsRssFeed::class),
                'delete' => Auth::user()->can('delete', NewsRssFeed::class),
                'viewNewsroom'    => Auth::user()->can('viewAny', NewsPerson::class)
            ],
            'filters' => Request::only(['search']),
        ]);
  }

  public function edit(NewsRssFeed $newsRssFeed): \Inertia\Response {
//    $NewsRssFeed = NewsRssFeed::where('slug', $id)->first();

    return Inertia::render(
        'NewsRssFeeds/{$id}/Edit', [
            'feed' => $newsRssFeed,
            'can'  => [
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]
    );
  }

  public function update(HttpRequest $request, NewsRssFeed $newsRssFeed): \Illuminate\Http\RedirectResponse {
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('news_rss_feeds')->ignore($newsRssFeed->id), // $newsRssFeed is the current model instance
        ],
        'url'  => 'required|active_url',
    ]);

//        $newsRssFeed = NewsRssFeed::find($request->id);

    $newsRssFeed->name = $request->name;
    $newsRssFeed->slug = \Str::slug($request->name);
    $newsRssFeed->url = $request->url;

    $newsRssFeed->save();

//    Log::info('Edited RSS Feed: ' . $newsRssFeed->name . '.');

    FetchRssFeedItemsJob::dispatch($newsRssFeed);

    return redirect()
        ->route('newsRssFeeds.show',
            [$newsRssFeed])
        ->with('message', 'RSS Feed Updated Successfully');

  }

  public function destroy(NewsRssFeed $newsRssFeed): \Illuminate\Http\RedirectResponse {
    $newsRssFeed->delete();
    return redirect()->route('newsRssFeeds.index')->with('message', 'RSS Feed Deleted Successfully');
  }

  public function error(): \Inertia\Response {
    return Inertia::render('NewsRssFeeds/Index');
  }


}
