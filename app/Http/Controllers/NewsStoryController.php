<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\NewsCity;
use App\Models\NewsFederalElectoralDistrict;
use App\Models\NewsPerson;
use App\Models\NewsProvince;
use App\Models\NewsStory;
use App\Models\NewsCategory;
use App\Models\NewsCountry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsStoryController extends Controller {

  public function __construct() {
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
  public function index() {
    $user = Auth::user();

    return Inertia::render('News/Index', [
        'newsStories' => NewsStory::with('image', 'user', 'newsCategory', 'newsCategorySub', 'city', 'province', 'federalElectoralDistrict', 'subnationalElectoralDistrict', 'newsStatus', 'video')
            ->when(Request::input('search'), function ($query, $search) {
              $lowerSearch = strtolower($search); // Convert search term to lowercase
                $query->whereRaw('LOWER(title) like ?', "%{$lowerSearch}%")
                    ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerSearch}%")
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
            ->whereNotNull('published_at') // Only return stories with a published_at date
            ->orderBy('published_at', 'desc')
            ->paginate(10, ['*'], 'news')
            ->withQueryString()
            ->through(fn($newsStory) => [
                'id'                           => $newsStory->id,
                'user'                         => [
                    'name' => $newsStory->user->name,
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
            ]),
        'filters'     => Request::only(['search']),
        'can'         => [
            'viewNewsroom' => optional($user)->can('viewAny', NewsPerson::class) ?: false,
        ]
    ]);
  }

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
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(HttpRequest $request) {

    $request->validate([
        'title'                             => 'unique:news_stories|required|string|max:255',
//        'body'                              => 'required|string',
        'content_json'                      => 'nullable|json',
        'news_category_id'                  => 'required|integer',
        'news_category_sub_id'              => 'nullable|integer',
        'city_id'                           => 'nullable|integer',
        'province_id'                       => 'nullable|integer',
        'federal_electoral_district_id'     => 'nullable|integer',
        'subnational_electoral_district_id' => 'nullable|integer',
        'type'                              => 'nullable|string|required_if:news_category_id,3' // Require type if news_category_id is 3 (Local News)
    ], [
        'body.required'                     => 'The content is required.',
        'news_category_id.required'         => 'The news category is required.',
        'type.required_if'                  => 'The news location is required when Local News is selected.',
    ]);


    $country = $this->getCountry();
    $newsStory = new NewsStory();
//        $this->fillNewsStoryAttributes($newsStory, $request);
    $newsStory->user_id = Auth::user()->id;
    $newsStory->title = htmlentities($request->title);
//    $newsStory->content = $request->body;
    $newsStory->content_json = $request->content_json;
    $newsStory->slug = \Str::slug($request->title);
    $newsStory->user_id = Auth::user()->id;
    $newsStory->news_category_id = $request->news_category_id;
    $newsStory->news_category_sub_id = $request->news_category_sub_id;
    $newsStory->city_id = $request->city_id;
    $newsStory->province_id = $request->province_id;
    $newsStory->news_federal_electoral_district_id = $request->federal_electoral_district_id;
    $newsStory->country_id = $country->id;
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
    $newsStory = NewsStory::query()->where('slug', $slug)->with('image', 'province')->firstOrFail();

    $canViewNewsroom = optional(Auth::user())->can('viewAny', NewsPerson::class);
    $canEditNewsStory = optional(Auth::user())->can('update', $newsStory);

    // Allow public access if the story status is 6
    // Otherwise, check permissions
    if (!($newsStory->status == 6 || $canViewNewsroom || $canEditNewsStory)) {
      throw new NotFoundHttpException('Story not available.');
    }

    return Inertia::render(
        'News/Stories/{$id}/Index',
        [
            'newsStory' => [
                'id'                           => $newsStory->id,
                'slug'                         => $newsStory->slug,
                'title'                        => html_entity_decode($newsStory->title),
//                'content'                      => html_entity_decode($newsStory->content),
                'content'                      => html_entity_decode($newsStory->content),
                'content_json'                 => $newsStory->content_json,
                'author'                       => $newsStory->user->name,
                'newsCategory'                 => $newsStory->newsCategory->name,
                'newsCategorySub'              => $newsStory->newsCategorySub->name ?? null,
                'city'                         => $newsStory->city->name ?? null,
                'province'                     => $newsStory->province->name ?? null,
                'federalElectoralDistrict'     => $newsStory->federalElectoralDistrict->name ?? null,
                'subnationalElectoralDistrict' => $newsStory->subnationalElectoralDistrict->name ?? null,
                'image'                        => $newsStory->image,
                'status'                       => $newsStory->newsStatus->name,
                'video'                        => $newsStory->video ?? null,
                'created_at'                   => $newsStory->created_at,
                'updated_at'                   => $newsStory->updated_at,
                'published_at'                 => $newsStory->published_at,
            ],
            'can'  => [
                'editNewsStory'   => optional(Auth::user())->can('edit', $newsStory) ?: false,
                'deleteNewsStory' => optional(Auth::user())->can('delete', $newsStory) ?: false,
                'viewNewsroom'    => optional(Auth::user())->can('viewAny', NewsPerson::class) ?: false
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
    $this->authorize('update', $newsStory);

    // Retrieve the country
    $country = $this->getCountry();

    // Retrieve all NewsCategories with their subcategories
    $categories = $this->getCategories();

    // Retrieve and combine Cities, Provinces, Federal Electoral Districts for searching
    $search = Request::input('search');
    $locationSearch = $this->getLocationSearch($search);

    return Inertia::render(
        'News/Stories/{$id}/Edit',
        [
            'country'        => $country,
            'categories'     => $categories,
            'locationSearch' => $locationSearch,
            'filters'        => Request::only(['search']),
            'newsStory'      => [
                'id'                                => $newsStory->id,
                'slug'                              => $newsStory->slug,
                'title'                             => html_entity_decode($newsStory->title),
//                'content'                           => html_entity_decode($newsStory->content),
                'content_json' => $newsStory->content_json,
                'user'                              => [
                    'name' => $newsStory->user->name,
                ],
                'news_category_id'                  => $newsStory->news_category_id ?? null,
                'news_category_sub_id'              => $newsStory->news_category_sub_id ?? null,
                'city_id'                           => $newsStory->city_id ?? null,
                'province_id'                       => $newsStory->province_id ?? null,
                'federal_electoral_district_id'     => $newsStory->federalElectoralDistrict->id ?? null,
                'subnational_electoral_district_id' => $newsStory->news_subnational_electoral_district_id ?? null,
                'image'                             => $newsStory->image,
                'status'                            => [
                    'id'   => $newsStory->newsStatus->id ?? null,
                    'name' => $newsStory->newsStatus->name ?? null,
                ],
                'video'                             => $newsStory->video ?? null,
                'created_at'                        => $newsStory->created_at,
                'published_at'                      => $newsStory->published_at,
//                    'content_json' => $post->content_json,
            ],
            'can'            => [
                'viewNewsroom' => Auth::user()->can('viewAny', NewsPerson::class)
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
    $this->authorize('update', $newsStory);

    $originalStatus = $newsStory->status;

    $validatedData = $request->validate([
        'title' => 'sometimes|required|string|max:255|' . Rule::unique('news_stories')->ignore($newsStory->id),
        'content_json' => 'sometimes|nullable|json',
        'status' => 'sometimes|required|exists:news_statuses,id',
        'news_category_id' => 'sometimes|required|integer',
        'news_category_sub_id' => 'sometimes|nullable|integer',
        'city_id' => 'sometimes|nullable|integer',
        'province_id' => 'sometimes|nullable|integer',
        'federal_electoral_district_id' => 'sometimes|nullable|integer',
        'subnational_electoral_district_id' => 'sometimes|nullable|integer',
    ]);

    // Updating the NewsStory
    if (array_key_exists('title', $validatedData)) {
      $newsStory->title = htmlentities($validatedData['title']);
      $newsStory->slug = \Str::slug($validatedData['title']);
    }
    if (array_key_exists('content_json', $validatedData)) {
      $newsStory->content_json = $validatedData['content_json'];
    }
    if (array_key_exists('status', $validatedData)) {
      $newsStory->status = $validatedData['status'];
    }
    if (array_key_exists('news_category_id', $validatedData)) {
      $newsStory->news_category_id = $validatedData['news_category_id'];
    }
    if (array_key_exists('news_category_sub_id', $validatedData)) {
      $newsStory->news_category_sub_id = $validatedData['news_category_sub_id'];
    }
    if (array_key_exists('city_id', $validatedData)) {
      $newsStory->city_id = $validatedData['city_id'];
    }
    if (array_key_exists('province_id', $validatedData)) {
      $newsStory->province_id = $validatedData['province_id'];
    }
    if (array_key_exists('federal_electoral_district_id', $validatedData)) {
      $newsStory->federal_electoral_district_id = $validatedData['federal_electoral_district_id'];
    }
    if (array_key_exists('subnational_electoral_district_id', $validatedData)) {
      $newsStory->subnational_electoral_district_id = $validatedData['subnational_electoral_district_id'];
    }

    // Check if the status is the only field that has been changed
    $onlyStatusChanged = $newsStory->isDirty('status') && $newsStory->getDirty() == ['status' => $validatedData['status']];

    $newsStory->save();

//    // Check if only the status was changed
//    $onlyStatusChanged = $originalStatus != $newsStory->status && $newsStory->wasChanged() && count($newsStory->getChanges()) == 1;

    // Customizing the success message
    $message = 'News Story Updated Successfully';
    if ($newsStory->status == 6) { // Replace '6' with your actual 'published' status ID
      $message = 'News Story Published Successfully';
    }

    // Return appropriate response
    if ($request->wantsJson()) {
      return response()->json(['message' => $message]);
    }

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


  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return RedirectResponse
   */
  public function destroy($id) {
    $this->authorize('delete', NewsStory::class);
    NewsStory::destroy($id);
    sleep(1);

    return redirect()->route('newsroom')->with('message', 'News Story Deleted Successfully');
  }


  public function changeStatus(HttpRequest $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'newsStory_id' => 'required|exists:news_stories,id',
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
    return redirect()->route('newsroom')->with('message', $message);
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
  private function getCategories() {
    return NewsCategory::with('newsCategorySubs')->get();
  }

  //    tec21: this search created with help from ChatGPT 2024-12-25
  //    combines cities, provinces and federalElectoralDistricts
  //    for easier NewsStory creation. It's also used on the edit page.

  private function getLocationSearch($search) {
    // Retrieve and combine Cities, Provinces, Federal Electoral Districts for searching
    $cities = NewsCity::with('province')
        ->when($search, function ($query) use ($search) {
          $query->where('news_cities.name', 'like', "%{$search}%");
        })
        ->orderBy('news_cities.name', 'asc')
        ->select('news_cities.id as city_id', 'news_cities.name', 'news_cities.type as type', 'news_provinces.name as province_name', 'news_provinces.id as province_id')
        ->leftJoin('news_provinces', 'news_cities.province_id', '=', 'news_provinces.id')
        ->get()->map(function ($city) {
          $city->type = strtolower($city->type); // Convert type to lowercase

          return $city;
        });

    $provinces = NewsProvince::when($search, function ($query) use ($search) {
      $query->where('name', 'like', "%{$search}%");
    })
        ->select('id as province_id', 'name', 'type')
        ->get()
        ->map(function ($province) {
          $province->type = 'province';

          return $province;
        });

    $federalElectoralDistricts = NewsFederalElectoralDistrict::with('province')
        ->when($search, function ($query) use ($search) {
          $query->where('news_federal_electoral_districts.name', 'like', "%{$search}%");
        })
        ->orderBy('news_federal_electoral_districts.name')
        ->select('news_federal_electoral_districts.id as federal_electoral_district_id', 'news_federal_electoral_districts.name as name', 'news_provinces.name as province_name', 'news_provinces.id as province_id')
        ->leftJoin('news_provinces', 'news_federal_electoral_districts.province_id', '=', 'news_provinces.id')
        ->get()
        ->map(function ($district) {
          $district->type = 'federalElectoralDistrict';

          return $district;
        });

    // Combine the results and return
    return $locations = $cities->concat($provinces)->concat($federalElectoralDistricts);
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


}
