<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\NewsRssFeedItemArchive;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class NewsRssArchiveController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Inertia\Response
   */
  public function index() {
    return Inertia::render('NewsRssArchive/Index', [
        'archive' => NewsRssFeedItemArchive::query()
            ->with('image.appSetting', 'newsRssFeed')
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
            ->through(fn($newsRssFeed) => [
                'id'          => $newsRssFeed->id,
                'feedName'    => $newsRssFeed->feedName,
                'feedSlug'    => $newsRssFeed->newsRssFeed->slug,
                'title'       => $newsRssFeed->title,
                'description' => $newsRssFeed->description,
                'url'         => $newsRssFeed->link,
                'pubDate'     => $newsRssFeed->pubDate,
                'image_url'   => $newsRssFeed->image_url,
                'image'       => $newsRssFeed->image ? new ImageResource($newsRssFeed->image) : null,
            ]),
        'filters' => Request::only(['search']),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
   */
  public function create() {
    return view('newsRssFeedItemsArchive.create');
    // Replace 'newsRssFeedItemsArchive.create' with your actual view name
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request) {
    $validatedData = $request->validate([
      // Validate your data here
    ]);

    NewsRssFeedItemArchive::create($validatedData);

    return redirect()->route('newsRssFeedItemsArchive.index')
        ->with('success', 'Archived item created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param NewsRssFeedItemArchive $newsRssFeedItemArchive
   * @return \Illuminate\Http\Response
   */
  public function show(NewsRssFeedItemArchive $newsRssFeedItemArchive) {
    return view('newsRssFeedItemsArchive.show', compact('newsRssFeedItemArchive'));
    // Replace 'newsRssFeedItemsArchive.show' with your actual view name
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param NewsRssFeedItemArchive $newsRssFeedItemArchive
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
   */
  public function edit(NewsRssFeedItemArchive $newsRssFeedItemArchive) {
    return view('newsRssFeedItemsArchive.edit', compact('newsRssFeedItemArchive'));
    // Replace 'newsRssFeedItemsArchive.edit' with your actual view name
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param NewsRssFeedItemArchive $newsRssFeedItemArchive
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, NewsRssFeedItemArchive $newsRssFeedItemArchive) {
    $validatedData = $request->validate([
      // Validate your data here
    ]);

    $newsRssFeedItemArchive->update($validatedData);

    return redirect()->route('newsRssFeedItemsArchive.index')
        ->with('success', 'Archived item updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param NewsRssFeedItemArchive $newsRssFeedItemArchive
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(NewsRssFeedItemArchive $newsRssFeedItemArchive) {
    $newsRssFeedItemArchive->delete();

    return redirect()->route('newsRssFeedItemsArchive.index')
        ->with('success', 'Archived item deleted successfully.');
  }
}
