<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Resources\NewsStoryResource;
use App\Http\Resources\VideoResource;
use App\Models\AppSetting;
use App\Models\NewsCity;
use App\Models\NewsFederalElectoralDistrict;
use App\Models\NewsPerson;
use App\Models\NewsProvince;
use App\Models\NewsStory;
use App\Models\NewsCategory;
use App\Models\NewsCountry;
use App\Models\NewsSubnationalElectoralDistrict;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;
use Inertia\Response;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsStoryController extends Controller {

  public function __construct() {
//        $this->middleware('auth');
    $this->middleware('can:edit,newsStory')->only(['edit', 'update']);
//        $this->middleware('can:edit,newsStory')->only(['edit', 'update']);
    $this->middleware('can:startStory,' . NewsStory::class)->only(['create', 'store']);
    $this->middleware('can:delete,newsStory')->only(['destroy']);
//        $this->authorizeResource(NewsStory::class, 'newsStory');

  }


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */


  public function index() {
    // Check if user is logged in
    if (Auth::check()) {
      // User is logged in, show the news index for logged-in users
      return Inertia::render('News/Index', [
          'newsStories' => $this->getNewsStories(),
          'filters'     => Request::only(['search']),
          'can'         => [
              'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class),
          ],
      ]);
    } else {
      // User is not logged in, show a different page
      return Inertia::render('LoggedOut/News/Stories/Index', [
          'newsStories' => $this->getNewsStories(),
          'filters'     => Request::only(['search']),
      ]);
    }
  }

  private function getNewsStories() {
    $user = Auth::user();

    return NewsStory::with('image', 'user', 'newsPerson.user', 'newsCategory', 'newsCategorySub', 'city', 'province', 'federalElectoralDistrict', 'subnationalElectoralDistrict', 'newsStatus', 'video')
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
        ->whereNotNull('published_at')
        ->orderBy('published_at', 'desc')
        ->paginate(10, ['*'], 'news')
        ->withQueryString()
        ->through(fn($newsStory) => [
            'id'                           => $newsStory->id,
            'user'                         => [
                'name' => $newsStory->user->name,
            ],
            'news_person'                  => [
                'id'   => $newsStory->news_person_id ?? null,
                'name' => $newsStory->newsPerson->user->name ?? null,
            ],
            'title'                        => html_entity_decode($newsStory->title),
            'slug'                         => $newsStory->slug,
            'newsCategory'                 => $newsStory->newsCategory->name ?? null,
            'newsCategorySub'              => $newsStory->newsCategorySub->name ?? null,
            'content'                      => $newsStory->content,
            'city'                         => $newsStory->city->name ?? null,
            'province'                     => $newsStory->province->name ?? null,
            'federalElectoralDistrict'     => $newsStory->federalElectoralDistrict->name ?? null,
            'subnationalElectoralDistrict' => $newsStory->subnationalElectoralDistrict->name ?? null,
            'image'                        => $newsStory->image,
            'status'                       => $newsStory->newsStatus->name,
            'video'                        => $newsStory->video ?? null,
            'created_at'                   => $newsStory->created_at,
            'published_at'                 => $newsStory->published_at,
        ]);
  }




//
//  public function index() {
//    $user = Auth::user();
//
//
//    return Inertia::render('News/Index', [
//        'newsStories' => NewsStory::with('image', 'user', 'newsPerson.user', 'newsCategory', 'newsCategorySub', 'city', 'province', 'federalElectoralDistrict', 'subnationalElectoralDistrict', 'newsStatus', 'video')
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
//            ->whereNotNull('published_at') // Only return stories with a published_at date
//            ->orderBy('published_at', 'desc')
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
//                'title'                        => html_entity_decode($newsStory->title),
//                'slug'                         => $newsStory->slug,
//                'newsCategory'                 => $newsStory->newsCategory->name ?? null,
//                'newsCategorySub'              => $newsStory->newsCategorySub->name ?? null,
//                'content'                      => $newsStory->content,
//                'city'                         => $newsStory->city->name ?? null,
//                'province'                     => $newsStory->province->name ?? null,
//                'federalElectoralDistrict'     => $newsStory->federalElectoralDistrict->name ?? null,
//                'subnationalElectoralDistrict' => $newsStory->subnationalElectoralDistrict->name ?? null,
//                'image'                        => $newsStory->image,
//                'status'                       => $newsStory->newsStatus->name,
//                'video'                        => $newsStory->video ?? null,
//                'created_at'                   => $newsStory->created_at,
//                'published_at'                 => $newsStory->published_at,
//            ]),
//        'filters'     => Request::only(['search']),
//        'can'         => [
//            'viewNewsroom' => optional($user)->can('viewAny', NewsPerson::class) ?: false,
//        ]
//    ]);
//  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create() {
    // Retrieve the country
    $country = $this->getCountry();

    // Retrieve all NewsCategories with their subcategories
    $categories = $this->getCategories();

    // Retrieve and combine Cities, Provinces, Federal Electoral Districts for searching
    $search = Request::input('search');
    $locationSearch = $this->getLocationSearch($search);

    return Inertia::render(
        'News/Stories/Create', [
            'country'        => $country,
            'categories'     => $categories,
            'locationSearch' => $locationSearch,
            'filters'        => Request::only(['search']),
            'can'            => [
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
            ]
        ]
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param HttpRequest $request
   * @return RedirectResponse
   */
  public function store(HttpRequest $request) {

    $validatedData = $request->validate([
        'title'                             => 'required|string|max:255|unique:news_stories',
//        'content_json'                      => 'nullable|json',
        'content'                           => 'nullable|string',
        'news_category_id'                  => 'required|integer',
        'news_category_sub_id'              => 'nullable|integer',
        'city_id'                           => 'nullable|integer',
        'province_id'                       => 'nullable|integer',
        'federal_electoral_district_id'     => 'nullable|integer',
        'subnational_electoral_district_id' => 'nullable|integer',
        'type'                              => 'nullable|string|required_if:news_category_id,3' // Type required if news_category_id is 3 (Local News)
    ], [
        'title.required'            => 'The title is required.',
        'news_category_id.required' => 'The news category is required.',
        'type.required_if'          => 'The news location is required when Local News is selected.',
    ]);

    // Sanitize the content using Mews\Purifier
    $sanitizedContent = Purifier::clean($validatedData['content']);

    $newsStory = new NewsStory([
        'title'                              => htmlentities($validatedData['title']), // Sanitize the title
//        'content_json'                       => $validatedData['content_json'],
        'content'                            => $sanitizedContent,
        'slug'                               => \Str::slug($validatedData['title']),
        'news_category_id'                   => $validatedData['news_category_id'],
        'news_category_sub_id'               => $validatedData['news_category_sub_id'],
        'city_id'                            => $validatedData['city_id'] ?? null,
        'province_id'                        => $validatedData['province_id'] ?? null,
        'news_federal_electoral_district_id' => $validatedData['federal_electoral_district_id'] ?? null,
        'country_id'                         => $this->getCountry()->id,
    ]);

    $newsStory->user_id = Auth::id(); // Directly get the authenticated user's ID
    $newsStory->news_person_id = Auth::user()->newsPerson->id;
    $newsStory->save();

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
  public function show($slug) {
    $newsStory = NewsStory::query()->where('slug', $slug)
        ->with([
            'image.appSetting',
            'province',
            'newsPerson.user',
            'newsCategory',
            'newsCategorySub',
            'city',
            'federalElectoralDistrict',
            'subnationalElectoralDistrict',
            'newsStatus'
        ])
        ->firstOrFail();

    $user = Auth::user();

    $canViewNewsroom = optional($user)->can('viewAny', NewsPerson::class);
    $canEditNewsStory = optional($user)->can('edit', $newsStory);

    // Allow public access if the story status is 6
    // Otherwise, check permissions
    if (!($newsStory->status == 6 || $canViewNewsroom || $canEditNewsStory)) {
      throw new NotFoundHttpException('Story not available.');
    }

    // Select the view based on the authentication status
    $component = $user ? 'News/Stories/{$id}/Index' : 'LoggedOut/News/Stories/{$id}/Index';

    return Inertia::render(
        $component,
        [
            'newsStory'         => (new NewsStoryResource($newsStory))->toArray(request()),
//            'newsStory' => [
//                'id'                           => $newsStory->id,
//                'slug'                         => $newsStory->slug,
//                'title'                        => $newsStory->title,
//                'content'                      => $newsStory->content,
////                'content_json'                 => $newsStory->content_json,
//                'author'                       => $newsStory->newsPerson->user->name ?? $newsStory->user->name,
//                'newsCategory'                 => $newsStory->newsCategory->name,
//                'newsCategorySub'              => $newsStory->newsCategorySub->name ?? null,
//                'city'                         => $newsStory->city->name ?? null,
//                'province'                     => $newsStory->province->name ?? null,
//                'federalElectoralDistrict'     => $newsStory->federalElectoralDistrict->name ?? null,
//                'subnationalElectoralDistrict' => $newsStory->subnationalElectoralDistrict->name ?? null,
//                'image'                        => $newsStory->image ? (new ImageResource($newsStory->image))->resolve() : null,
//                'status'                       => $newsStory->newsStatus->name,
//                'video'                        => $newsStory->video ?? null,
//                'created_at'                   => $newsStory->created_at,
//                'updated_at'                   => $newsStory->updated_at,
//                'published_at'                 => $newsStory->published_at,
//            ],
            'can'       => [
                'editNewsStory'   => $canEditNewsStory,
                'deleteNewsStory' => optional(Auth::user())->can('delete', $newsStory) ?: false,
                'viewNewsroom'    => $canViewNewsroom
            ]
        ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param NewsStory $newsStory
   * @return Response
   */
  public function edit(NewsStory $newsStory) {
    // Eager load related models
    $newsStory->load(['newsPerson.user', 'image.appSetting', 'video.appSetting']);

    // Retrieve the country
//    $country = $this->getCountry();

    // Retrieve all NewsCategories with their subcategories
//    $categories = $this->getCategories();

    // Retrieve and combine Cities, Provinces, Federal Electoral Districts for searching
//    $search = Request::input('search');
//    $locationSearch = $this->getLocationSearch($search);

    // Check for cached content
    $cacheKey = 'news_story:' . $newsStory->id;
    $cachedContent = json_decode(Redis::get($cacheKey), true);

    $useCachedContent = false;
    if ($cachedContent && strtotime($cachedContent['timestamp']) > strtotime($newsStory->updated_at)) {
      $useCachedContent = true;
    }

    return Inertia::render('News/Stories/{$id}/Edit', [
//        'country'        => $country,
//        'categories'     => $categories,
//        'locationSearch' => $locationSearch,
//        'filters'        => Request::only(['search']),
        'newsStory'         => (new NewsStoryResource($newsStory))->toArray(request()),
//        'newsStory'     => [
//            'id'                           => $newsStory->id,
//            'slug'                         => $newsStory->slug,
//            'title'                        => $newsStory->title,
//            'content'                      => $newsStory->content,
//            'newsPerson'                   => [
//                'id'   => $newsStory->newsPerson->id ?? null,
//                'name' => $newsStory->newsPerson->user->name ?? null,
//            ],
//            'category'                     => $newsStory->getCachedCategory() ?? [
//                    'id'          => null,
//                    'name'        => null,
//                    'description' => null,
//                ],
//            'subCategory'                  => $newsStory->getCachedSubCategory() ?? [
//                    'id'          => null,
//                    'name'        => null,
//                    'description' => null,
//                ],
//            'city'                         => $newsStory->getCachedCity() ?? [
//                    'id'   => null,
//                    'name' => null,
//                ],
//            'province'                     => $newsStory->getCachedProvince() ?? [
//                    'id'   => null,
//                    'name' => null,
//                ],
//            'federalElectoralDistrict'     => $newsStory->getCachedFederalElectoralDistrict() ?? [
//                    'id'   => null,
//                    'name' => null,
//                ],
//            'subnationalElectoralDistrict' => $newsStory->getCachedSubnationalElectoralDistrict() ?? [
//                    'id'   => null,
//                    'name' => null,
//                ],
//            'image'                        => $newsStory->image ? (new ImageResource($newsStory->image))->resolve() : null,
//            'status'                       => $newsStory->getCachedStatus() ?? [
//                    'id'   => null,
//                    'name' => null,
//                ],
//            'video'                        => $newsStory->video ? (new VideoResource($newsStory->video))->resolve() : null,
//            'created_at'                   => $newsStory->created_at,
//            'published_at'                 => $newsStory->published_at,
//        ],
        'cachedContent' => $useCachedContent ? $cachedContent : null,
        'can'           => [
            'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class),
            'publish'      => Auth::user()->can('canPublish', $newsStory),
        ]
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param HttpRequest $request
   * @param NewsStory $newsStory
   * @return RedirectResponse
   */
  public function update(HttpRequest $request, NewsStory $newsStory) {

    // Original status
    $originalStatus = $newsStory->status;

    // Validate the input data
    $validatedData = $request->validate([
        'title'                             => 'required|string|max:255|' . Rule::unique('news_stories')->ignore($newsStory->id),
        'content'                           => 'required|string|max:5000',
//        'content_json'                      => 'nullable|json',
        'status'                            => 'required|exists:news_statuses,id',
        'news_category_id'                  => 'required|integer',
        'news_category_sub_id'              => 'nullable|integer',
        'city_id'                           => 'nullable|integer',
        'province_id'                       => 'nullable|integer',
        'federal_electoral_district_id'     => 'nullable|integer',
        'subnational_electoral_district_id' => 'nullable|integer',
        'news_person_id'                    => 'required|exists:news_people,id'
    ]);

    // Log request data
    Log::info('Request data: ', $request->all());

    // Sanitize the content using Mews\Purifier
    $sanitizedContent = Purifier::clean($validatedData['content']);

    // Ensure sanitized content is not empty if original content was not empty
    if (empty($sanitizedContent) && !empty($validatedData['content'])) {
      Log::error('Sanitizer removed all content');
    }

    // Log sanitized content
    Log::info('Sanitized content: ', ['content' => $sanitizedContent]);

    // Sanitize the title using htmlentities
    $sanitizedTitle = htmlentities($validatedData['title']);

    // Update the news story
    $newsStory->news_person_id = $validatedData['news_person_id'];
    $newsStory->title = $sanitizedTitle;
    $newsStory->slug = \Str::slug($sanitizedTitle);
    $newsStory->news_category_id = $validatedData['news_category_id'];
    $newsStory->news_category_sub_id = $validatedData['news_category_sub_id'] ?? null;
    $newsStory->content = $sanitizedContent ?? null;
    $newsStory->city_id = $validatedData['city_id'] ?? null;
    $newsStory->province_id = $validatedData['province_id'] ?? null;
    $newsStory->news_federal_electoral_district_id = $validatedData['federal_electoral_district_id'] ?? null;
    $newsStory->news_subnational_electoral_district_id = $validatedData['subnational_electoral_district_id'] ?? null;
    $newsStory->status = $validatedData['status'];
//    $newsStory->content_json = $validatedData['content_json'];

    // Log news story before saving
    Log::info('NewsStory before save: ', $newsStory->toArray());

    // Check if only the status has changed
    $onlyStatusChanged = $newsStory->isDirty('status') && $newsStory->getDirty() == ['status' => $validatedData['status']];

    // Save the news story
    $newsStory->save();

    // Customizing the success message
    $message = 'News Story Updated Successfully';
    if ($newsStory->status == 6) { // Replace '6' with your actual 'published' status ID
      $message = 'News Story Published Successfully';
    }

    // Return appropriate response
//    if ($request->wantsJson()) {
//      return response()->json(['message' => $message]);
//    }

    if ($onlyStatusChanged) {
      return redirect()->route('newsroom')->with('message', $message);
    } else {
      return redirect()->route('news/story.show', [$newsStory->slug])->with('message', $message);
    }
  }

  // Content is saved through this method, it uses Tiptap
  // on the front end to save the file as the user types.
  // The title is saved through the update method.
  public function save(HttpRequest $request) {

    $newsStory = NewsStory::find($request->id);

    $request->validate([
        'title' => ['required', 'string', 'max:255', Rule::unique('news_stories')->ignore($newsStory->id)],
        'body'  => 'required|string',
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

  public function cacheContent(HttpRequest $request): JsonResponse {
    $validatedData = $request->validate([
        'id'                           => 'required|exists:news_stories,id',
        'slug'                         => 'required|string',
        'title'                        => 'required|string|max:255',
        'status'                       => 'required|array',
        'content'                      => 'required|string|max:5000',
        'newsPerson'                   => 'required|array',
        'category'                     => 'required|array',
        'subCategory'                  => 'nullable|array',
        'city'                         => 'nullable|array',
        'province'                     => 'nullable|array',
        'federalElectoralDistrict'     => 'nullable|array',
        'subnationalElectoralDistrict' => 'nullable|array',
    ]);

    $cacheKey = 'news_story:' . $validatedData['id'];
    $content = [
        'id'                           => $validatedData['id'],
        'slug'                         => $validatedData['slug'],
        'title'                        => $validatedData['title'],
        'status'                       => $validatedData['status'],
        'content'                      => $validatedData['content'],
        'newsPerson'                   => $validatedData['newsPerson'],
        'category'                     => $validatedData['category'],
        'subCategory'                  => $validatedData['subCategory'],
        'city'                         => $validatedData['city'],
        'province'                     => $validatedData['province'],
        'federalElectoralDistrict'     => $validatedData['federalElectoralDistrict'],
        'subnationalElectoralDistrict' => $validatedData['subnationalElectoralDistrict'],
        'timestamp'                    => now(),
    ];

    Redis::set($cacheKey, json_encode($content));
    Redis::expire($cacheKey, 259200); // Set expiration to 72 hours

    return response()->json(['success' => true, 'message' => 'Content cached successfully.']);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return RedirectResponse
   */
  public function destroy(NewsStory $newsStory) {

//    try {
//      $this->authorize('delete', $id);
//      // Proceed with deletion logic...
//    } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
//      return back()->with('error', 'You must be a producer or assignment editor to delete news stories.');
//    }


//    dd('what?');
//    $this->authorize('delete', NewsStory::class);
//    NewsStory::destroy($newsStory);
    $newsStory->delete();

    return redirect()->route('newsroom')->with('message', 'News Story Deleted Successfully');
  }


  public function changeStatus(HttpRequest $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'newsStory_id'  => 'required|exists:news_stories,id',
        'new_status_id' => 'required|exists:news_statuses,id'
    ]);

    // Retrieve the news story by ID
    $newsStory = NewsStory::findOrFail($validatedData['newsStory_id']);

    if ($request->new_status_id === 6) {
      $newsStory->published_at = date('Y-m-d H:i:s');
    }

    // Update the status
    $newsStory->status = $validatedData['new_status_id'];
    $newsStory->save();

    // Customize the success message
    $message = 'News Story Status Updated Successfully';
    if ($newsStory->status == $validatedData['new_status_id']) {
      // Add additional messages if needed, based on the status
      // For example, if the status indicates 'published'
      if ($newsStory->status == 6) { // Replace with actual published status ID
        $message = 'News Story Published Successfully';
      }
    }

    // Redirect with success message
    return redirect()->route('newsroom');
  }


  private function getCountry() {
    // Retrieve the country ID from AppSettings
    $countryId = AppSetting::first()->country_id ?? null;

    // Fetch the corresponding country
    $country = null;
    if ($countryId) {
      $country = NewsCountry::find($countryId);
    }

    return $country;
  }


// Retrieve all NewsCategories with their subcategories
  private function getCategories(): \Illuminate\Database\Eloquent\Collection|array {
    return NewsCategory::with('newsCategorySubs')->get();
  }

  // Retrieve all NewsCategories with their subcategories
  public function fetchCategories(): JsonResponse {
    // Attempt to retrieve categories from cache
    $categories = Cache::rememberForever('news_categories', function () {
      // Fetch categories and subcategories from the database
      return NewsCategory::with('newsCategorySubs')->get()->map(function ($category) {
        return [
            'id'            => $category->id,
            'name'          => $category->name,
            'description'   => $category->description,
            'subCategories' => $category->newsCategorySubs->map(function ($subCategory) {
              return [
                  'id'          => $subCategory->id,
                  'name'        => $subCategory->name,
                  'description' => $subCategory->description,
              ];
            }),
        ];
      });
    });

    return response()->json($categories);
  }

  //    tec21: this search created with help from ChatGPT 2024-12-25
  //    combines cities, provinces and federalElectoralDistricts
  //    for easier NewsStory creation. It's also used on the edit page.

  private function getLocationSearch($search) {
    // Retrieve Cities with their Provinces
    $cities = NewsCity::with('province:id,name')
        ->when($search, function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->select('id as city_id', 'name', 'type', 'province_id')
        ->get()
        ->map(function ($city) {
          return [
              'id'       => $city->city_id,
              'name'     => $city->name,
              'province' => [
                  'id'   => $city->province_id,
                  'name' => $city->province->name,
              ],
              'type'     => strtolower($city->type),
          ];
        });


    // Retrieve Provinces
    $provinces = NewsProvince::when($search, function ($query) use ($search) {
      $query->where('name', 'like', "%{$search}%");
    })
        ->select('id as province_id', 'name')
        ->get()
        ->map(function ($province) {
          return [
              'id'   => $province->province_id,
              'name' => $province->name,
              'type' => 'province',
          ];
        });

    // Retrieve Federal Electoral Districts with their Provinces
    $federalElectoralDistricts = NewsFederalElectoralDistrict::with('province:id,name')
        ->when($search, function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->select('id as federal_electoral_district_id', 'name', 'province_id')
        ->get()
        ->map(function ($district) {
          return [
              'id'            => $district->federal_electoral_district_id,
              'name'          => $district->name,
              'province_id'   => $district->province_id,
              'province_name' => $district->province->name ?? null,
              'type'          => 'federalElectoralDistrict',
          ];
        });

    // Retrieve Subnational Electoral Districts with their Provinces
    $subnationalElectoralDistricts = NewsSubnationalElectoralDistrict::with('province:id,name')
        ->when($search, function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->select('id as subnational_electoral_district_id', 'name', 'province_id')
        ->get()
        ->map(function ($district) {
          return [
              'id'       => $district->id,
              'name'     => $district->name,
              'province' => [
                  'id'   => $district->province_id,
                  'name' => $district->province->name ?? null,
              ],
              'type'     => 'subnationalElectoralDistrict',
          ];
        });

    // Combine the results and return
    return $locations = $cities->concat($provinces)->concat($federalElectoralDistricts)->concat($subnationalElectoralDistricts);
  }

  private function fillNewsStoryAttributes(NewsStory $newsStory, $request) {
    $newsStory->title = htmlentities($request->title);
    $newsStory->content = htmlentities($request->body);
    // ... other attributes ...
    $newsStory->slug = \Str::slug($request->title);
    $newsStory->news_category_id = $request->news_category_id;
    $newsStory->news_category_sub_id = $request->news_category_sub_id;
    $newsStory->city_id = $request->city_id;
    $newsStory->province_id = $request->province_id;
    $newsStory->news_federal_electoral_district_id = $request->federal_electoral_district_id;
    $newsStory->country_id = $this->getCountry()->id;
    // $newsStory->content_json = $request->content_json; // Uncomment if needed
  }


  /**
   * Show the list of all News Locations.
   *
   * @return JsonResponse
   */
  public function fetchNewsCities(): JsonResponse {
    $cities = $this->getLocationSearch(null);

    return response()->json($cities);
  }


}
