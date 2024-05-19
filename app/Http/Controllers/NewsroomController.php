<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Resources\NewsStoryResource;
use App\Models\AppSetting;
use App\Models\NewsStatus;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\NewsStory;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Nette\Utils\DateTime;

class NewsroomController extends Controller {

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

    // Fetch news stories with necessary relationships and apply filters
    $newsStoriesQuery = NewsStory::with([
        'image.appSetting',
        'video.appSetting',
        'newsPerson.user',
    ])
        ->when(Request::input('search'), function ($query, $search) {
          $lowerSearch = strtolower($search); // Convert search term to lowercase
          $query->whereRaw('LOWER(title) like ?', "%{$lowerSearch}%")
              ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerSearch}%")
              ->orWhereHas('newsPerson', function ($query) use ($lowerSearch) {
                $query->whereHas('user', function ($q) use ($lowerSearch) {
                  $q->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                });
              })
              ->orWhereHas('user', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhereHas('newsCategory', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhereHas('newsCategorySub', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhereHas('city', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhereHas('province', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhereHas('federalElectoralDistrict', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhereHas('subnationalElectoralDistrict', function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
              })
              ->orWhere(function ($query) use ($search) {
                // Check if search query could be a year
                if (preg_match('/^\d{4}$/', $search)) { // Regex to match a 4-digit year
                  $query->whereYear('published_at', $search);
                }
              })
              ->orWhere(function ($query) use ($search) {
                // Check if search query could be a year-month
                try {
                  $date = \Carbon\Carbon::createFromFormat('Y-m', $search);
                  $query->whereYear('published_at', $date->format('Y'))
                      ->whereMonth('published_at', $date->format('m'));
                } catch (\Exception $e) {
                  // If it's not a valid year-month, do nothing
                }
              })
              ->orWhere(function ($query) use ($search) {
                // Check if search query could be a date
                try {
                  $date = \Carbon\Carbon::parse($search);
                  $query->whereDate('published_at', $date->format('Y-m-d'));
                } catch (\Exception $e) {
                  // If it's not a valid date, do nothing
                }
              });
        })
        ->whereNotIn('status', [6, 7]) // Exclude certain statuses
        ->latest();


    // Paginate and transform the data using the resource
    $paginatedNewsStories = $newsStoriesQuery->paginate(10)->withQueryString();
    $newsStories = NewsStoryResource::collection($paginatedNewsStories);

    // tec21: 2024-02-09 excluding New, Published, and Hidden from what will be displayed in the change status pop-up
    $newsStoryStatuses = NewsStatus::all()->reject(function ($status) {
      return in_array($status->id, [1, 6, 7]);
    });


    // Paginate and transform the data
    // Apply the resource to the collection
//    $newsStories = NewsStoryResource::collection($newsStoriesQuery);
//    $newsStories = $newsStoriesQuery->paginate(10)->withQueryString()->through(fn($newsStory) => [
//        'id' => $newsStory->id,
//        'user' => ['name' => $newsStory->user->name],
//        'news_person' => [
//            'id' => $newsStory->news_person_id ?? null,
//            'name' => $newsStory->newsPerson->user->name ?? null,
//        ],
//        'title' => $newsStory->title,
//        'slug' => $newsStory->slug,
//        'category'                     => $newsStory->getCachedCategory() ?? [
//                'id'          => null,
//                'name'        => null,
//                'description' => null,
//            ],
//        'subCategory'                  => $newsStory->getCachedSubCategory() ?? [
//                'id'          => null,
//                'name'        => null,
//                'description' => null,
//            ],
//        'city' => $newsStory->getCachedCity()->name ?? null,
//        'province' => $newsStory->getCachedProvince()->name ?? null,
//        'federalElectoralDistrict' => $newsStory->getCachedFederalElectoralDistrict()->name ?? null,
//        'subnationalElectoralDistrict' => $newsStory->getCachedSubnationalElectoralDistrict()->name ?? null,
//        'image' => $newsStory->image ? (new ImageResource($newsStory->image))->resolve() : null,
//        'status' => $newsStory->getCachedStatus() ?? ['id' => null, 'name' => null],
//        'created_at' => $newsStory->created_at,
//        'updated_at' => $newsStory->updated_at,
//        'published_at' => $newsStory->published_at ?? null,
//        'can' => [
//            'editNewsStory' => Auth::user()->can('edit', $newsStory),
//            'deleteNewsStory' => Auth::user()->can('delete', $newsStory),
//            'publishNewsStory' => Auth::user()->can('canPublish', $newsStory)
//        ]
//    ]);

    return Inertia::render('Newsroom/Index', [
        'newsStories' => $newsStories,
        'filters' => Request::only(['search']),
        'newsStoryStatuses' => $newsStoryStatuses,
        'can' => [
            'createNewsStory' => Auth::user()->can('startStory', NewsStory::class),
        ]
    ]);

//    return Inertia::render('Newsroom/Index', [
//        'newsStories'       => NewsStory::with('image.appSetting', 'user', 'newsPerson.user', 'newsCategory', 'newsCategorySub', 'city', 'province', 'federalElectoralDistrict', 'subnationalElectoralDistrict', 'newsStatus', 'video')
//            ->when(Request::input('search'), function ($query, $search) {
//              $lowerSearch = strtolower($search); // Convert search term to lowercase
//              $query->whereRaw('LOWER(title) like ?', "%{$lowerSearch}%")
//                  ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerSearch}%")
//                  ->orWhereHas('newsPerson', function ($query) use ($lowerSearch) {
//                    $query->whereHas('user', function ($q) use ($lowerSearch) {
//                      $q->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                    });
//                  })
//                  ->orWhereHas('user', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhereHas('newsCategory', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhereHas('newsCategorySub', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhereHas('city', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhereHas('province', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhereHas('federalElectoralDistrict', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhereHas('subnationalElectoralDistrict', function ($query) use ($lowerSearch) {
//                    $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
//                  })
//                  ->orWhere(function ($query) use ($search) {
//                    // Check if search query could be a year
//                    if (preg_match('/^\d{4}$/', $search)) { // Regex to match a 4-digit year
//                      $query->whereYear('published_at', $search);
//                    }
//                  })
//                  ->orWhere(function ($query) use ($search) {
//                    // Check if search query could be a year-month
//                    try {
//                      $date = \Carbon\Carbon::createFromFormat('Y-m', $search);
//                      $query->whereYear('published_at', $date->format('Y'))
//                          ->whereMonth('published_at', $date->format('m'));
//                    } catch (\Exception $e) {
//                      // If it's not a valid year-month, do nothing
//                    }
//                  })
//                  ->orWhere(function ($query) use ($search) {
//                    // Check if search query could be a date
//                    try {
//                      $date = \Carbon\Carbon::parse($search);
//                      $query->whereDate('published_at', $date->format('Y-m-d'));
//                    } catch (\Exception $e) {
//                      // If it's not a valid date, do nothing
//                    }
//                  });
//            })
//            ->whereNotIn('status', [6, 7]) // don't return stories with a status of 6 published or 7 hidden
//            ->latest()
//            ->paginate(10, ['*'], 'news')
//            ->withQueryString()
//            ->through(fn($newsStory) => [
//                'id'                           => $newsStory->id,
//                'user'                         => [
//                    'name' => $newsStory->user->name,
//                ],
//                'news_person'                  => [
//                    'id'   => $newsStory->news_person_id ?? null,
//                    'name' => $newsStory->newsPerson->user->name ?? null,
//                ],
//                'title'                        => $newsStory->title,
//                'slug'                         => $newsStory->slug,
//                'newsCategory'                 => $newsStory->getCachedCategory()->name ?? null,
//                'newsCategorySub'              => $newsStory->getCachedSubCategory()->name ?? null,
//                'city'                         => $newsStory->getCachedCity()->name ?? null,
//                'province'                     => $newsStory->getCachedProvince()->name ?? null,
//                'federalElectoralDistrict'     => $newsStory->getCachedFederalElectoralDistrict()->name ?? null,
//                'subnationalElectoralDistrict' => $newsStory->getCachedSubnationalElectoralDistrict()->name ?? null,
//                'image'                        => $newsStory->image ? (new ImageResource($newsStory->image))->resolve() : null,
//                'status' => $newsStory->getCachedStatus() ?? [
//                        'id'   => null,
//                        'name' => null,
//                    ],
//                'created_at'                   => $newsStory->created_at,
//                'updated_at'                   => $newsStory->updated_at,
//                'published_at'                 => $newsStory->published_at ?? null,
//                'can'                          => [
//                    'editNewsStory'    => Auth::user()->can('edit', $newsStory),
//                    'deleteNewsStory'  => Auth::user()->can('delete', $newsStory),
//                    'publishNewsStory' => Auth::user()->can('canPublish', $newsStory)
//                ]
//            ]),
//        'filters'           => Request::only(['search']),
//        'newsStoryStatuses' => $newsStoryStatuses,
//        'can'               => [
//            'createNewsStory' => Auth::user()->can('startStory', NewsStory::class),
////            'editNewsStory'    => Auth::user()->can('edit', NewsStory::class),
//        ]
//    ]);
  }

  public function publish(HttpRequest $request, NewsStory $newsStory) {
//    $newsStory = NewsStory::find($request->id);
    $newsStory->published_at = date('Y-m-d H:i:s');
    $newsStory->status = 6;
    $newsStory->update();

    return redirect()->route('newsroom')->with('message', 'News Story Published Successfully');
  }
}
