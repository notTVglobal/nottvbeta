<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Resources\NewsStoryResource;
use App\Models\AppSetting;
use App\Models\NewsStatus;
use App\Traits\SearchableNewsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Models\NewsStory;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Nette\Utils\DateTime;

class NewsroomController extends Controller {

  use SearchableNewsTrait;

  public function __construct() {
    $this->middleware('can:publish,newsStory')->only(['publish']);
  }


  public function index() {

    // tec21: 2024-02-09 excluding New, Published and Hidden from what will be displayed in the change status pop-up
    // this is to force the user to "Approve" then "Publish" stories using the dedicated publish button.
    // plus, I was having trouble getting the published_at date to get added to the model on the NewsController.changeStatus().
    // We are hiding "Hidden" because we have no way of making a hidden story visible again.
    // And New is not necessary, it's only used for when a story is first created.
//    $newsStoryStatuses = NewsStatus::all()->reject(function ($status) {
//      return in_array($status->id, [1, 6, 7]);
//    });

    // Fetch app settings once and use it in caching


//    $newsStoryStatuses = NewsStatus::all()->reject(function ($status) {
//      return in_array($status->id, [1, 6, 7]);
//    });

    $search = Request::input('search');
    $publishedSearch = Request::input('publishedSearch');

    // Base query with common conditions
    $baseQuery = NewsStory::with([
        'image.appSetting',
        'video.appSetting',
        'newsPerson.user',
    ]);

    // Handle news stories search
    $newsStoriesQuery = clone $baseQuery;
    $this->applySearch($newsStoriesQuery, $search, [0, 1, 2, 3, 4, 5]); // Assuming these are the statuses you want to include
    $newsStoriesQuery->latest();

    // Handle published stories search
    $publishedNewsStoriesQuery = clone $baseQuery;
    $this->applySearch($publishedNewsStoriesQuery, $publishedSearch, [6]);
    $publishedNewsStoriesQuery->latest();

    // Apply policy check to filter published stories
//    $publishedNewsStories = $publishedNewsStoriesQuery->get()->filter(function ($newsStory) {
//      return Auth::user()->can('edit', $newsStory);
//    });

    // Paginate and transform the data using the resource
    $paginatedNewsStories = $newsStoriesQuery->paginate(10)->withQueryString();
    $publishedNewsStories = $publishedNewsStoriesQuery->paginate(10)->withQueryString();
    $newsStories = NewsStoryResource::collection($paginatedNewsStories);
    $publishedStories = NewsStoryResource::collection($publishedNewsStories);

    // Exclude certain statuses from the change status pop-up
    $newsStoryStatuses = NewsStatus::all()->reject(function ($status) {
      return in_array($status->id, [1, 6, 7]);
    });

    return Inertia::render('Newsroom/Index', [
        'publishedStories'  => $publishedStories,
        'newsStories'       => $newsStories,
        'filters'           => Request::only(['search', 'publishedSearch']),
        'newsStoryStatuses' => $newsStoryStatuses,
        'can'               => [
            'createNewsStory' => Auth::user()->can('startStory', NewsStory::class),
        ]
    ]);
  }

  public function publish(HttpRequest $request, NewsStory $newsStory): \Illuminate\Http\RedirectResponse {
    try {
      // Set the publication date to the current date and time
      $newsStory->published_at = date('Y-m-d H:i:s');

      // Update the status to 'published'
      $newsStory->status = 6;

      // Save the changes to the database
      if ($newsStory->update()) {
        // Log a message indicating the news story was successfully updated
        Log::info('News story published successfully', ['news_story_id' => $newsStory->id]);

        // Redirect to the newsroom with a success message
        return redirect()->route('newsroom')->with(['success' => 'News story successfully published.']);
      } else {
        // Log a warning if the update failed
        Log::warning('Failed to update news story', ['news_story_id' => $newsStory->id]);

        // Redirect to the newsroom with an error message
        return redirect()->route('newsroom')->withErrors(['error' => 'Failed to publish news story.']);
      }
    } catch (\Exception $e) {
      // Log the exception message
      Log::error('Error publishing news story', [
          'news_story_id' => $newsStory->id,
          'error'         => $e->getMessage(),
      ]);

      // Redirect to the newsroom with an error message
      return redirect()->route('newsroom')->withErrors(['error' => 'An error occurred while publishing the news story.']);
    }
  }

  /**
   * @param Builder $baseQuery
   * @param mixed $publishedSearch
   * @return Builder
   */
  protected function getPublishedNewsStoriesQuery(Builder $baseQuery, mixed $publishedSearch): Builder {
    $publishedNewsStoriesQuery = clone $baseQuery;
    if ($publishedSearch) {
      $lowerPublishedSearch = strtolower($publishedSearch);
      $publishedNewsStoriesQuery->where(function ($query) use ($lowerPublishedSearch) {
        $query->whereRaw('LOWER(title) like ?', "%{$lowerPublishedSearch}%")
            ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerPublishedSearch}%")
            ->orWhereHas('newsPerson', function ($query) use ($lowerPublishedSearch) {
              $query->whereHas('user', function ($q) use ($lowerPublishedSearch) {
                $q->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
              });
            })
            ->orWhereHas('user', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhereHas('newsCategory', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhereHas('newsCategorySub', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhereHas('city', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhereHas('province', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhereHas('federalElectoralDistrict', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhereHas('subnationalElectoralDistrict', function ($query) use ($lowerPublishedSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerPublishedSearch}%");
            })
            ->orWhere(function ($query) use ($lowerPublishedSearch) {
              // Check if search query could be a year
              if (preg_match('/^\d{4}$/', $lowerPublishedSearch)) { // Regex to match a 4-digit year
                $query->whereYear('published_at', $lowerPublishedSearch);
              }
            })
            ->orWhere(function ($query) use ($lowerPublishedSearch) {
              // Check if search query could be a year-month
              try {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $lowerPublishedSearch);
                $query->whereYear('published_at', $date->format('Y'))
                    ->whereMonth('published_at', $date->format('m'));
              } catch (\Exception $e) {
                // If it's not a valid year-month, do nothing
              }
            })
            ->orWhere(function ($query) use ($lowerPublishedSearch) {
              // Check if search query could be a date
              try {
                $date = \Carbon\Carbon::parse($lowerPublishedSearch);
                $query->whereDate('published_at', $date->format('Y-m-d'));
              } catch (\Exception $e) {
                // If it's not a valid date, do nothing
              }
            });
      });
    }

    return $publishedNewsStoriesQuery;
  }
}
