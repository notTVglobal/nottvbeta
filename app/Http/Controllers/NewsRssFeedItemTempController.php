<?php

namespace App\Http\Controllers;

use App\Models\NewsPerson;
use App\Models\NewsRssFeed;
use App\Models\NewsRssFeedItemTemp;
use App\Models\NewsStory;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class NewsRssFeedItemTempController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */

    public function index()
    {
//      $items = NewsRssFeedItemTemp::all();

      return Inertia::render('NewsRssFeeds/Index', [
          'feeds' => NewsRssFeedItemTemp::query()
              ->with('newsRssFeed')
              ->orderBy('pubDate', 'desc')
              ->when(Request::input('search'), function ($query, $search) {
                // Remove periods and spaces from the search term
                $searchTerm = str_replace(['.', ' '], '', $search);

                // Adjust the query to ignore periods and spaces in title and description
                $query->where(function ($q) use ($searchTerm) {
                  $q->whereRaw("REPLACE(REPLACE(title, ' ', ''), '.', '') LIKE ?", ["%{$searchTerm}%"])
                      ->orWhereRaw("REPLACE(REPLACE(description, ' ', ''), '.', '') LIKE ?", ["%{$searchTerm}%"]);
                });
              })
              ->paginate(10, ['*'], 'rss')
              ->withQueryString()
              ->through(fn($newsRssFeedTempItem) => [
                  'id'          => $newsRssFeedTempItem->id,
                  'feedName'    => $newsRssFeedTempItem->newsRssFeed->name,
                  'feedSlug'    => $newsRssFeedTempItem->newsRssFeed->slug,
                  'title'       => $newsRssFeedTempItem->title,
                  'description' => $newsRssFeedTempItem->description,
                  'url'         => $newsRssFeedTempItem->link,
                  'pubDate'     => $newsRssFeedTempItem->pubDate,
                  'image_url'   => $newsRssFeedTempItem->image_url,
                  'is_saved'    => $newsRssFeedTempItem->is_saved,
//                'image'       => $newsRssFeed->image ? new ImageResource($newsRssFeed->image) : null,
              ]),
          'filters' => Request::only(['search']),
          'can'     => [
//                'editNewsStory' => Auth::user()->can('update', NewsStory::class),
              'createNewsStory' => Auth::user()->can('create', NewsStory::class),
              'manageFeeds' => Auth::user()->can('manage', NewsRssFeed::class),
              'viewNewsroom'    => Auth::user()->can('viewAny', NewsPerson::class)
          ]
      ]);
    }
      // Replace 'newsRssFeedItemsTemp.index' with your actual view name


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
      return view('newsRssFeedItemsTemp.create');
      // Replace 'newsRssFeedItemsTemp.create' with your actual view name
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
      $validatedData = $request->validate([
        // Validate your data here
      ]);

      NewsRssFeedItemTemp::create($validatedData);

      return redirect()->route('newsRssFeedItemsTemp.index')
          ->with('success', 'Feed item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  NewsRssFeedItemTemp $newsRssFeedItemTemp
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(NewsRssFeedItemTemp $newsRssFeedItemTemp)
    {
      return view('newsRssFeedItemsTemp.show', compact('newsRssFeedItemTemp'));
      // Replace 'newsRssFeedItemsTemp.show' with your actual view name
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  NewsRssFeedItemTemp $newsRssFeedItemTemp
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(NewsRssFeedItemTemp $newsRssFeedItemTemp)
    {
      return view('newsRssFeedItemsTemp.edit', compact('newsRssFeedItemTemp'));
      // Replace 'newsRssFeedItemsTemp.edit' with your actual view name
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(HttpRequest $request, NewsRssFeedItemTemp $newsRssFeedItemTemp)
    {
      $validatedData = $request->validate([
          'is_saved' => 'required|boolean', // Validates that is_saved is a boolean
      ]);

//      $newsRssFeedItemTemp = NewsRssFeedItemTemp::find($validatedData['id']);
//      if (!$newsRssFeedItemTemp) {
//        return response()->json(['message' => 'Feed item not found.'], 404);
//      }

//      $newsRssFeedItemTemp->is_saved = $validatedData['is_saved'] ? 1 : 0;
//      $newsRssFeedItemTemp->save();

      $newsRssFeedItemTemp->is_saved = $validatedData['is_saved'];
      $newsRssFeedItemTemp->save();

      // Retrieve the related NewsRssFeed to get the slug
//      $newsRssFeed = NewsRssFeed::find($validatedData['news_rss_feed_id']);
//      if (!$newsRssFeed) {
//        return response()->json(['message' => 'News RSS Feed not found.'], 404);
//      }

      return response()->json(['message' => 'Feed item updated successfully.']);
//      return response()->json(['message' => 'Feed item updated successfully.', 'success' => true]);
//      return to_route('newsRssFeeds.show', $newsRssFeed->slug)->with('success', 'Feed item updated successfully.');

//      $newsRssFeedItemTemp = NewsRssFeedItemTemp::find($request->id);
//      $newsRssFeedItemTemp->is_saved = $request->is_saved;
//      $newsRssFeedItemTemp->save();

//      $newsRssFeedId = $request->news_rss_feed_id;


//      $newsRssFeed = NewsRssFeed::find($request->news_rss_feed_id);

        // Redirect to the specific newsRssFeed show route with success message
//      return response()->json(['message' => 'Feed item updated successfully.']);
//      return to_route('newsRssFeeds.show', $newsRssFeed->slug)->with('success', 'Feed item updated successfully.');
//      return Inertia::location(route('newsRssFeeds.show', $newsRssFeed->slug));
//        return redirect()->route('newsRssFeeds.show', $newsRssFeedSlug)
//            ->with('success', 'Feed item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  NewsRssFeedItemTemp $newsRssFeedItemTemp
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(NewsRssFeedItemTemp $newsRssFeedItemTemp)
    {
      $newsRssFeedItemTemp->delete();

      return redirect()->route('newsRssFeedItemsTemp.index')
          ->with('success', 'Feed item deleted successfully.');
    }

  /**
   * Save a feed item.
   *
   * @param  \App\Models\NewsRssFeedItemTemp  $newsRssFeedItemTemp
   * @return \Illuminate\Http\RedirectResponse
   */
  public function saveItem(NewsRssFeedItemTemp $newsRssFeedItemTemp)
  {
    $newsRssFeedItemTemp->is_saved = true;
    $newsRssFeedItemTemp->saved_by_user_id = auth()->user()->id;
    $newsRssFeedItemTemp->save();
    return redirect()->back()->with('success', 'Feed item updated successfully.');
//    return response()->json(['message' => 'Feed item updated successfully.']);

//    return redirect()->route('newsRssFeedItemsTemp.index')
//        ->with('success', 'Feed item saved successfully.');
  }
}
