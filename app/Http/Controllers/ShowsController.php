<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Show;
use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\ShowEpisode;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\AppSetting;
//use http\QueryString;
use App\Models\Video;
use http\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ShowsController extends Controller
{

    public function __construct()
    {

//        $this->middleware('can:viewAny,show')->only(['index']);
        $this->middleware('can:viewShowManagePage,show')->only(['manage']);
        // tec21: this policy isn't working vvv
//        $this->middleware('can:editShowManagePage,show')->only(['changeEpisodeStatus']);
        $this->middleware('can:edit,show')->only(['edit']);
//        $this->middleware('can:create,show')->only(['store']);
        $this->middleware('can:createEpisode,show')->only(['createEpisode']);
        $this->middleware('can:viewEpisodeManagePage,show')->only(['manageEpisode']);

    }


////////////  INDEX
///////////////////

    public function index()
    {

        return Inertia::render('Shows/Index', [

            'shows' => Show::with('team', 'user', 'image', 'episodes', 'showStatus')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->whereHas('episodes', function ($query) {
                    // Filter episodes with episodeStatus of 7 (Published)
                    $query->where('show_episode_status_id', 7);
                })
                ->where(function ($query) {
                    // Filter shows with showStatus of 1 or 2 (New or Active)
                    $query->where('show_status_id', 1)->orWhere('show_status_id', 2);
                })
                ->latest()
                ->paginate(6, ['*'], 'shows')
                ->withQueryString()
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'team_id' => $show->team_id,
                    'teamName' => $show->team->name,
                    'teamSlug' => $show->team->slug,
                    'showRunnerId' => $show->user_id,
                    'showRunnerName' => $show->user->name,
                    'image' => [
                        'id' => $show->image->id,
                        'name' => $show->image->name,
                        'folder' => $show->image->folder,
                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                        'cloud_folder' => $show->image->cloud_folder,
                    ],
                    'slug' => $show->slug,
                    'totalEpisodes' => $show->episodes->count(),
                    'status' => $show->showStatus->name,
                    'statusId' => $show->showStatus->id,
                    'copyrightYear' => $show->created_at->format('Y'),
                    'first_release_year' => $show->first_release_year,
                    'last_release_year' => $show->last_release_year,
                    'categoryName' => $show->showCategory->name,
                    'categorySubName' => $show->showCategorySub->name,
                    'can' => [
                        'editShow' => Auth::user()->can('editShow', $show),
                        'viewShow' => Auth::user()->can('viewShowManagePage', $show)
                    ]
                ]),
            'newestEpisodes' => ShowEpisode::with('show', 'image')
                ->where(function ($query) {
                    // Filter episodes with episodeStatus of 7 (Published)
                    $query->where('show_episode_status_id', 7);
                })
                ->whereHas('show', function ($query) {
                    // Filter shows with showStatus of 1 or 2 (New or Active)
                    $query->where('show_status_id', 1)->orWhere('show_status_id', 2);
                })
                ->orderBy('release_dateTime', 'desc') // Sort by release_date in descending order
                ->paginate(5, ['*'], 'episodes')
                ->through(fn($showEpisode) => [
                    'id' => $showEpisode->id,
                    'name' => $showEpisode->name,
                    'description' => \Str::limit($showEpisode->description, 100, ' ...'),
                    'image' => [
                        'id' => $showEpisode->image->id,
                        'name' => $showEpisode->image->name,
                        'folder' => $showEpisode->image->folder,
                        'cdn_endpoint' => $showEpisode->appSetting->cdn_endpoint,
                        'cloud_folder' => $showEpisode->image->cloud_folder,
                    ],
                    'slug' => $showEpisode->slug,
                    'showName' => $showEpisode->show->name,
                    'showSlug' => $showEpisode->show->slug,
                    'releaseDate' => $showEpisode->release_dateTime,
                    'categoryName' => $showEpisode->show->showCategory->name,
                    'categorySubName' => $showEpisode->show->showCategorySub->name,
                    'release_date' => $showEpisode->release_dateTime,
                ]),
//            'mostAnticipated' => Show::with('image')
//                ->paginate(3, ['*'], 'trending')
//                ->through(fn($show) => [
//                    'id' => $show->id,
//                    'name' => $show->name,
//                    'description' => $show->description,
//                    'image' => [
//                        'id' => $show->image->id,
//                        'name' => $show->image->name,
//                        'folder' => $show->image->folder,
//                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
//                        'cloud_folder' => $show->image->cloud_folder,
//                    ],
//                    'slug' => $show->slug,
//                    'copyrightYear' => $show->created_at->format('Y'),
//                    'categoryName' => $show->showCategory->name,
//                    'categorySubName' => $show->showCategorySub->name,
//                ]),
            'comingSoon' => Show::with('image', 'episodes')
                ->whereHas('episodes', function ($query) {
                    // Filter episodes with episodeStatus of 7 (Published)
                    $query->where('show_episode_status_id', 6);
                })
                ->where(function ($query) {
                    // Filter shows with showStatus of 1 or 2 (New or Active)
                    $query->where('show_status_id', 1)->orWhere('show_status_id', 2);
                })
                ->latest()
                ->paginate(3, ['*'], 'comingSoon')
                ->through(fn($show) => [
                    'id' => $show->id,
                    'name' => $show->name,
                    'description' => $show->description,
                    'image' => [
                        'id' => $show->image->id,
                        'name' => $show->image->name,
                        'folder' => $show->image->folder,
                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                        'cloud_folder' => $show->image->cloud_folder,
                    ],
                    'slug' => $show->slug,
                    'copyrightYear' => $show->created_at->format('Y'),
                    'categoryName' => $show->showCategory->name,
                    'categorySubName' => $show->showCategorySub->name,
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewShows' => Auth::user()->can('view', Show::class),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }



////////////  CREATE AND STORE
//////////////////////////////

    public function create()
    {
        $categories = ShowCategory::all();
        $sub_categories = ShowCategorySub::all();

             return Inertia::render('Shows/Create', [
                 'teams' => Team::query()
                     ->where('user_id', Auth::user()->id)
                     ->paginate(5, ['*'], 'teams')
                     ->withQueryString()
                     ->through(fn($team) => [
                         'id' => $team->id,
                         'name' => $team->name,
                         'slug' => $team->slug,
                         'can' => [
                             'manageTeam' => Auth::user()->can('manage', $team)
                         ]
                     ]),
                 'userId' => Auth::user()->id,
                 'categories' => $categories,
                 'subCategories' => $sub_categories,
        ]);

    }


    public function store(HttpRequest $request)
    {
        $request->validate([
            'name' => 'unique:shows|required|string|max:255',
//            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required|string',
            'user_id' => 'required',
            'team_id' => 'required|integer|min:1',
            'category' => 'required',
            'sub_category' => 'nullable',
            'instagram_name' => 'nullable|string|max:30',
            'telegram_url' => 'nullable|active_url',
            'twitter_handle' => 'nullable|string|min:4|max:15',
            'notes' => 'nullable|string|max:1024',
        ],
            [ 'team_id' => 'A team must be selected.']);
        Show::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'team_id' => $request->team_id,
            'show_category_id' => $request->category,
            'show_category_sub_id' => $request->sub_category,
            'slug' => \Str::slug($request->name),
            'www_url' => $request->www_url,
            'instagram_name' => $request->instagram_name,
            'telegram_url' => $request->telegram_url,
            'twitter_handle' => $request->twitter_handle,
            'notes' => $request->notes,
            'isBeingEditedByUser_id' => $request->user_id,
            'first_release_year' => \Carbon\Carbon::now()->format('Y'),
        ]);

        // Use this route to return
        // the user to the new show page.

        $show = Show::query()->where('name', $request->name)->firstOrFail();
        return redirect()->route('shows.manage', $show)->with('success', 'Show Created Successfully');
//        return Inertia::render('Shows/{$id}/Manage', $show)->with('message', 'Show Created Successfully');

        // Use this route to return the
        // user back to the team page
        // instead of the new show page.

//      return redirect()->route('teams.manage', $request->team_id)->with('message', 'Show Created Successfully');
    }



////////////  SHOW
//////////////////

    public function show(Show $show)
    {

        $teamId = $show->team_id;

        $latestEpisodeWithVideo = $show->episodes->reverse()->first(function ($episode) {
            return !empty($episode->video_id);
        });
//        dd($latestEpisodeWithVideo->video);

        $latestEpisodeWithVideoUrl = $show->episodes->reverse()->first(function ($episode) {
            return !empty($episode->video_url);
        });

//        $firstPlayFile = $show->episodes->first(function ($episode) {
//            return !empty($episode->video_id);
//        });
//
//        $firstPlayUrl = $show->episodes->first(function ($episode) {
//            return !empty($episode->video_id);
//        });

//        $firstPlayFile = $episode->video_id ?? '';
//        $firstPlayUrl = $episode->video_url ?? '';
//
//        if ($firstPlayFile !== null) {
//            $firstPlay = $firstPlayFile;
//        } else $firstPlay = $firstPlayUrl;

//        $firstPlay = Video::all()->reject(function (Video $video) {
//        return $video->video_id === false;
//    })->map(function (Video $video) {
//        return $video;
//    });


        // get the first video with a video_id or video_url.
        // this is hard coded for now. The creator/team will
        // decide if they want the newest episode first, or
        // episode 1 first. Later on, people who have already
        // been watching the show will resume where they left
        // off.
//        $firstPlay = $showEpisode;
        //

        return Inertia::render('Shows/{$id}/Index', [
            'show' => [
                'name' => $show->name,
                'description' => $show->description,
                'showRunner' => $show->user->name,
                'slug' => $show->slug,
//                'poster' => $show->image->name,
                'image' => [
                    'id' => $show->image->id,
                    'name' => $show->image->name,
                    'folder' => $show->image->folder,
                    'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                    'cloud_folder' => $show->image->cloud_folder,
                ],
//                'firstPlay' => [
//                    'file_name' => $episode->video->file_name ?? '',
//                    'cdn_endpoint' => $episode->appSetting->cdn_endpoint ?? '',
//                    'folder' => $episode->video->folder ?? '',
//                    'cloud_folder' => $episode->video->cloud_folder ?? '',
//                    'upload_status' => $episode->video->upload_status ?? '',
//                ],
                // TODO: firstPlay returns a single video... replace with a show_playlist which needs to be created.
                'firstPlayVideo' => [
                    'file_name' => $latestEpisodeWithVideo->video->file_name ?? '',
                    'cdn_endpoint' => $latestEpisodeWithVideo->appSetting->cdn_endpoint ?? '',
                    'folder' => $latestEpisodeWithVideo->video->folder ?? '',
                    'cloud_folder' => $latestEpisodeWithVideo->video->cloud_folder ?? '',
                    'upload_status' => $latestEpisodeWithVideo->video->upload_status ?? '',
                ],
                'firstPlayVideoUrl' => $latestEpisodeWithVideoUrl->video_url ?? '',
                'copyrightYear' => $show->created_at->format('Y'),
                'first_release_year' => $show->first_release_year,
                'last_release_year' => $show->last_release_year,
                'www_url' => $show->www_url,
                'instagram_name' => $show->instagram_name,
                'telegram_url' => $show->telegram_url,
                'twitter_handle' => $show->twitter_handle,
                'categoryName' => $show->showCategory->name,
                'categorySubName' => $show->showCategorySub->name,
            ],

            ////////////////
            // tec21: this returns all columns from the showEpisode and image table
            // find a way to limit the query to only what we need (see my previous
            // attempt below)
            //
//            'episodes' => ShowEpisode::latest()->where('show_id', $show->id)->get(),
//            'episodes' => ShowEpisode::latest()->paginate(5),

//            'episodes' => ShowEpisode::with('show', 'image')
//                ->where('show_id', $show->id)
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                })
//                ->latest()
//                ->paginate(5)
//                ->when(Request::input('search'), function ($query, $search) {
//                    $query->where('name', 'like', "%{$search}%");
//                }),

            'episodes' => ShowEpisode::with('image', 'show', 'showEpisodeStatus')
                ->where('show_id', $show->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->where(function ($query) {
                    // Filter episodes with episodeStatus of 7
                    $query->where('show_episode_status_id', 7);
                })
                ->latest()
                ->paginate(8, ['*'], 'episodes')
                ->withQueryString()
                ->through(fn($showEpisode) => [
                    'id' => $showEpisode->id,
                    'name' => $showEpisode->name,
                    'slug' => $showEpisode->slug,
                    'episode_number' => $showEpisode->episode_number,
                    'created_at' => $showEpisode->created_at,
                    'image' => [
                        'id' => $showEpisode->image->id,
                        'name' => $showEpisode->image->name,
                        'folder' => $showEpisode->image->folder,
                        'cdn_endpoint' => $showEpisode->appSetting->cdn_endpoint,
                        'cloud_folder' => $showEpisode->image->cloud_folder,
                    ],
                ]),
            'creators' => TeamMember::where('team_id', $teamId)
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->select('users.*', 'team_members.user_id')
                ->latest()
                ->paginate(10, ['*'], 'creators')
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                ]),
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
                'poster' => $show->team->image->name,
            ],
            'can' => [
                'manageShow' => Auth::user()->can('manage', $show),
                'editShow' => Auth::user()->can('edit', $show),
                'viewCreator' => Auth::user()->can('viewCreator', User::class),
            ]
        ]);
    }


////////////  MANAGE
////////////////////

    public function manage(Show $show)
    {

        $episodeStatuses = DB::table('show_episode_statuses')->get()->toArray();
        $filteredStatuses = array_slice($episodeStatuses, 0, -2);

        return Inertia::render('Shows/{$id}/Manage', [
            'show' => [
                'name' => $show->name,
                'description' => $show->description,
                'showRunner' => $show->user->name,
                'slug' => $show->slug,
                'image' => [
                    'id' => $show->image->id,
                    'name' => $show->image->name,
                    'folder' => $show->image->folder,
                    'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                    'cloud_folder' => $show->image->cloud_folder,
                ],
                'copyrightYear' => $show->created_at->format('Y'),
                'categoryId' => $show->showCategory->id,
                'categoryName' => $show->showCategory->name,
                'categoryDescription' => $show->showCategory->description,
                'subCategoryId' => $show->showCategorySub->id,
                'subCategoryName' => $show->showCategorySub->name,
                'subCategoryDescription' => $show->showCategorySub->description,
                'notes' => $show->notes,
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episodes' => ShowEpisode::with('image', 'show', 'showEpisodeStatus')
                ->where('show_id', $show->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(5, ['*'], 'shows')
                ->withQueryString()
                ->through(fn($showEpisode) => [
                    'id' => $showEpisode->id,
                    'name' => $showEpisode->name,
                    'image' => [
                        'id' => $show->image->id,
                        'name' => $show->image->name,
                        'folder' => $show->image->folder,
                        'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                        'cloud_folder' => $show->image->cloud_folder,
                    ],
                    'slug' => $showEpisode->slug,
                    'episodeNumber' => $showEpisode->episode_number,
                    'notes' => $showEpisode->notes,
                    'episodeStatus' => $showEpisode->showEpisodeStatus->name,
                    'episodeStatusId' => $showEpisode->showEpisodeStatus->id,
                ]),
            'episodeStatuses' => $filteredStatuses,
            'can' => [
                'editShow' => auth()->user()->can('edit', $show),
                'createEpisode' => auth()->user()->can('createEpisode', $show),
                'createAssignment' => auth()->user()->can('editShow', $show),
                'goLive' => auth()->user()->can('goLive', $show),
            ]

        ]);
    }


////////////  EDIT
//////////////////

    public function edit(Show $show)
    {
//        $team = Show::query()->where('id', $show->id)->pluck('team_id')->firstOrFail();

        // Currently this queries all images in the database
        // this needs to be changed to limit the query.
        //
        DB::table('users')->where('id', Auth::user()->id)->update([
           'isEditingShow_id' => $show->id,
        ]);

        DB::table('shows')->where('id', $show->id)->update([
            'isBeingEditedByUser_id' => Auth::user()->id,
        ]);

//        function getPoster($show){
//            $getPoster = Image::query()
//                ->where('show_id', $show->id)
//                ->pluck('name')
//                ->first();
//            if(!empty($getPoster)){
//                $poster = $getPoster;
//            } else {
//                $poster = 'EBU_Colorbars.svg.png';
//            }
//            return $poster;
//        }

        $categories = ShowCategory::all();
        $sub_categories = ShowCategorySub::all();

//        return Inertia::render('Shows/{$id}/Edit', [
//            'show' => $show,
//            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
//            'showRunner' => User::query()->where('id', $show->user_id)->pluck('id','name')->firstOrFail(),
//            'poster' => getPoster($show),
//            'categories' => $categories,
//            'subCategories' => $sub_categories,
//            'showCategoryId' => $show->showCategory->id,
//            'showCategoryName' => $show->showCategory->name,
//            'showCategoryDescription' => $show->showCategory->description,
//
////            'can' => [
////                'viewShows' => Auth::user()->can('view', Show::class),
////                'editShow' => Auth::user()->can('edit', Show::class),
////                'viewCreator' => Auth::user()->can('viewCreator', User::class),
////            ]
//        ]);

        return Inertia::render('Shows/{$id}/Edit', [
            'show' => $show,
            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
            'showRunner' => User::query()->where('id', $show->user_id)->pluck('id','name')->firstOrFail(),
            'image' => [
                'id' => $show->image->id,
                'name' => $show->image->name,
                'folder' => $show->image->folder,
                'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                'cloud_folder' => $show->image->cloud_folder,
            ],
            'categories' => $categories,
            'subCategories' => $sub_categories,
            'showCategory' => $show->showCategory,
            // sub-categories not enabled yet...
//            'showCategorySub' => $show->showCategorySub,

//            'can' => [
//                'viewShows' => Auth::user()->can('view', Show::class),
//                'editShow' => Auth::user()->can('edit', Show::class),
//                'viewCreator' => Auth::user()->can('viewCreator', User::class),
//            ]
        ]);
    }


////////////  UPDATE
////////////////////


    public function update(HttpRequest $request, Show $show)
    {
         // validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shows')->ignore($show->id)],
            'description' => 'required',
            'category' => 'required',
            'sub_category' => 'nullable',
            'www_url' => 'nullable|active_url',
            'instagram_name' => 'nullable|string|max:30',
            'telegram_url' => 'nullable|active_url',
            'twitter_handle' => 'nullable|string|min:4|max:15',
            'notes' => 'nullable|string|max:1024',
        ]);


        // update the show
        $show->name = $request->name;
        $show->description = $request->description;
        $show->show_category_id = $request->category;
        $show->show_category_sub_id = $request->sub_category;
        $show->slug = \Str::slug($request->name);
        $show->www_url = $request->www_url;
        $show->instagram_name = $request->instagram_name;
        $show->telegram_url = $request->telegram_url;
        $show->twitter_handle = $request->twitter_handle;
        $show->notes = $request->notes;
        $show->save();
        sleep(1);



        // gather the data needed to render the Manage page
        // this is all redundant. It's all contained in the
        // teams.manage route above. But I (tec21) don't know
        // how to simplify this *frustrated*.

        // redirect
        return redirect(route('shows.manage', [$show->slug]))->with('success', 'Show Updated Successfully');

//        return Inertia::render('Shows/{$id}/Manage', [
//            // responses need to be limited to only
//            // the information required with ->only()
//            // https://inertiajs.com/responses
//            'show' => $show,
//            'posterName' => Image::query()->where('id', $show->image_id)->pluck('name')->firstOrFail(),
//            'team' => Team::query()->where('id', $show->team_id)->firstOrFail(),
//            'showRunnerName' => User::query()->where('id', $show->user_id)->pluck('name')->firstOrFail(),
//        ])->with('message', 'Show Updated Successfully');
    }

    public function updateNotes(HttpRequest $request)
    {
        // get the show
        $id = $request->showId;
        $show = Show::find($id);

        // validate the request
        $request->validate([
            'notes' => 'nullable|string|max:1024',
        ]);

        // update the show notes
        $show->notes = $request->notes;
        $show->save();
        sleep(1);

        return $show;
    }




////////////  DESTROY
/////////////////////

    public function destroy(Show $show)
    {
        $show->delete();
        sleep(1);

        // redirect
        return redirect()->route('shows')->with('message', 'Show Deleted Successfully');
    }


    ////////////////////////////////////////////////////////////////////////////////////
    ///                                EPISODES                                     ///
    ////////////////////////////////////////////////////////////////////////////////////


////////////  CREATE EPISODE
////////////////////////////
///
/// tec21: this might be better
/// in the ShowEpisodes Controller.

    public function createEpisode(Show $show)
    {

        return Inertia::render('Shows/{$id}/Episodes/Create', [
            'show' => [
                'name' => $show->name,
                'id' => $show->id,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'image' => [
                    'id' => $show->image->id,
                    'name' => $show->image->name,
                    'folder' => $show->image->folder,
                    'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                    'cloud_folder' => $show->image->cloud_folder,
                ],
                'showCategoryName' => $show->showCategory->name,
                'categorySubName' => $show->showCategorySub->name,
            ],
            'team' => Team::query()->where('id', $show->team_id)->first(),
        ]);
    }


////////////  MANAGE EPISODE
////////////////////////////
///
/// tec21: this might be better
/// in the ShowEpisodes Controller.

    public function manageEpisode(Show $show, ShowEpisode $showEpisode) {

        return Inertia::render('Shows/{$id}/Episodes/{$id}/Manage', [
            'show' => [
                'name' => $show->name,
                'slug' => $show->slug,
                'showRunner' => $show->user->name,
                'image' => [
                    'id' => $show->image->id,
                    'name' => $show->image->name,
                    'folder' => $show->image->folder,
                    'cdn_endpoint' => $show->appSetting->cdn_endpoint,
                    'cloud_folder' => $show->image->cloud_folder,
                ],
                'copyrightYear' => $show->created_at->format('Y'),
            ],
            'team' => [
                'name' => $show->team->name,
                'slug' => $show->team->slug,
            ],
            'episode' => $showEpisode,
            'can' => [
                'editEpisode' => auth()->user()->can('editEpisode', $show),
                'goLive' => auth()->user()->can('goLive', $show),
            ]

        ]);

    }
////////////  CHANGE EPISODE STATUS
////////////////////////////
///

    public function changeEpisodeStatus(HttpRequest $request, Show $show)
    {
        // if publishing an episode... episode_status_id === 7
        // set release year and dateTime
        // Carbon::now();

        try {
            // Get the episode ID and new status from the request
            $episodeId = $request->input('episode_id');
            $newStatusId = $request->input('new_status_id');

            if ($newStatusId === 7) {
                $releaseDateTime = Carbon::now();
                $releaseYear = Carbon::now()->year;
            }

            // Find the episode by ID
            $episode = ShowEpisode::find($episodeId);

            if (!$episode) {
                return response()->json(['message' => 'Episode not found'], 404);
            }

            // Update the episode's status
            $episode->show_episode_status_id = $newStatusId;
            $episode->release_dateTime = $releaseDateTime ?? null;
            $episode->release_year = $releaseYear ?? null;
            $episode->save();

            // If successful, return a success response
            return response()->json(['message' => 'Episode status updated successfully']);

            // If successful, you can return a success message
//            return Redirect::back()->with('success', 'Episode status changed successfully');
        } catch (\Exception $e) {
            // If an error occurs, return an error response
            return response()->json(['error' => 'Error changing episode status: ' . $e->getMessage()], 500);
        }



    }

}
