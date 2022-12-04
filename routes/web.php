<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CreatorsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShowsController;
use App\Http\Controllers\ShowEpisodesPosterController;
use App\Http\Controllers\ShowsPosterController;
use App\Http\Controllers\TeamsLogoController;
use App\Http\Controllers\ShowEpisodeController;
use App\Http\Controllers\MovieUploadController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\TeamMembersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WhitepaperController;
use App\Models\User;
use App\Models\Show;
use App\Models\ShowEpisode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/home', function () {
    if (Auth::user()->role_id != 4) {
        return redirect('/stream');
    } return redirect('/dashboard');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/stream');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return view('auth.verify-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/terms', function () {
    return redirect('/terms-of-service');
})->name('terms');


Route::get('/privacy', function () {
    return redirect('/privacy-policy');
})->name('privacy');

Route::get('/whitepaper', [WhitepaperController::class, 'show'])->name('whitepaper.show');


// BEGIN ROUTES FOR
// Logged In Users
///////////

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/stream', function () {
        return Inertia::render('Stream');
    })->name('stream');

    Route::get('/upgrade', function () {
        return Inertia::render('Upgrade');
    })->name('upgrade');


// Dashboard
///////////
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->can('viewDashboard', 'App\Models\Creator')
        // tec21: it doesn't like the ->middleware option.
        // The ->can option works well.
        //
//        ->middleware('can:viewDashboard,creator')
        ->name('dashboard');


// News (formerly Posts)
///////////
    Route::resource('news',NewsPostController::class);

    Route::get('/news', [NewsPostController::class, 'index'])
        ->can('viewPremium', 'App\Models\User')
        ->name('news');

//    Route::get('/posts/create', [PostController::class, 'create'])
//        ->can('viewPremium', 'App\Models\User')
//        ->name('posts.create');
//
//    Route::get('/posts/edit', [PostController::class, 'edit'])
//        ->can('viewPremium', 'App\Models\User')
//        ->name('posts.edit');
//
//    Route::post('/posts', [PostController::class, 'store'])
//        ->name('posts.store');

//    Route::get('/posts', function () {
//        return Inertia::render('Posts/Index');
//    })->can('viewPremium', 'App\Models\User')
//        ->name('posts');

// Channels
///////////
    // List all channels
    Route::get('/channels', function () {
        return Inertia::render('Channels');
    })->can('viewVip', 'App\Models\User')
        ->name('channels');

    // Admin manage the channels
    Route::get('/admin/channels', function () {
        return Inertia::render('Admin/Channels/Index');
    })->can('viewAdmin', 'App\Models\User')
        ->name('admin.channels.index');


// Shop
///////////
    Route::resource('shop',ShopController::class);
    // List all products
    Route::get('/shop', [ShopController::class, 'index'])
        ->name('shop');


// Schedule
///////////

    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule');


// Go Live
///////////

    Route::get('/golive', function () {
        return Inertia::render('GoLive');
    })->can('viewCreator', 'App\Models\User')
        ->name('golive');

// Invite
///////////
/// We'll make a public version of this page where a creator
/// enters a code they receive in their email to start the
/// sign up process.

    Route::get('/invite', function () {
        return Inertia::render('Invite');
    })->can('viewCreator', 'App\Models\User')
        ->name('invite');

// For Testing
///////////

    Route::get('/video', function () {
        return Inertia::render('Video');
    })->can('viewAdmin', 'App\Models\User')
        ->name('video');

    // temp page to test Stores
    Route::get('/quiz', function () {
        return Inertia::render('QuizHome');
    })->can('viewAdmin', 'App\Models\User')
        ->name('quiz');

// Admin Pages
///////////

    Route::get('/admin/settings', function () {
        return Inertia::render('Admin/Settings');
    })->can('viewAdmin', 'App\Models\User')
        ->name('admin.settings');

    Route::get('/admin/shows', [AdminController::class, 'showsIndex'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.shows');

    Route::get('/admin/teams', [AdminController::class, 'teamsIndex'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.teams');

    // temp page to test Stores
    Route::get('/quiz', function () {
        return Inertia::render('QuizHome');
    })->can('viewAdmin', 'App\Models\User')
        ->name('quiz');



// Creator Resources
    // Begin middleware authorization
    // allow creators access to the
    // following pages.
///////////
    Route::middleware([
        'can:viewAny, App\Models\Creator'
    ])->group(function () {


    // Teams
    ///////////
        Route::resource('teams',TeamsController::class);

        // List all teams
        Route::get('/teams', [TeamsController::class, 'index'])
            //        ->middleware('can:viewAny,App\Models\User')
            ->name('teams.index');

        // Create a team
        Route::get('/teams/create', [TeamsController::class, 'create'])
            ->middleware('can:createTeam,App\Models\Team')
            ->name('teams.create');

        // Add new team to database
        Route::post('/teams', [TeamsController::class, 'store'])
            ->middleware('can:createTeam,App\Models\Team')
            ->name('teams.store');

        // Display teams manage page
        Route::get('/teams/{team}/manage', [TeamsController::class, 'manage'])
            ->middleware('can:viewTeamManagePage,team')
            ->name('teams.manage');

        // Edit team
        Route::get('/teams/{team}/edit', [TeamsController::class, 'edit'])
//            ->middleware('can:edit,team')
            ->name('teams.edit');

        // Team Members
        Route::resource('teamMembers',TeamMembersController::class);

            // Add team member
            Route::post('/teams/addTeamMember', [TeamMembersController::class, 'attach'])
                ->name('teams.addTeamMember');

            // Remove team member
            Route::post('/teams/removeTeamMember', [TeamMembersController::class, 'detach'])
                ->name('teams.removeTeamMember');

    });

// Creators
///////////
    // Creators resource
    Route::resource('creators',CreatorsController::class);
    // Display creator page
    Route::get('/creators/{creator}', [CreatorsController::class, 'show'])
        ->name('creators.show');
    // Get list of creators
    Route::get('/api/creators', [CreatorsController::class, 'getCreators'])
        ->name('creators.getCreators');


// Training
///////////
    Route::get('/training', function () {
        return Inertia::render('Training');
    })->can('viewCreator', 'App\Models\User')
        ->name('training');

// Shows
///////////
    // Shows resource
    Route::resource('shows', ShowsController::class);
    // Display shows index page
    Route::get('/shows', [ShowsController::class, 'index'])
        ->can('viewPremium', 'App\Models\User')
        ->name('shows');
    // Display shows manage page
    Route::get('/shows/{show}/manage', [ShowsController::class, 'manage'])
        ->middleware('can:viewShowManagePage,show')
        ->name('shows.manage');
    // Display shows edit page
    Route::get('/shows/{show}/edit', [ShowsController::class, 'edit'])
        ->middleware('can:editShow,show')
        ->name('shows.edit');
    // Display shows create page
    Route::get('/shows/create', [ShowsController::class, 'create'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.create');

    ///////////////
    // tec21: move these into the ShowEpisodeController section below.
    // Display episode create page
    Route::get('/shows/{show}/episode/create', [ShowsController::class, 'createEpisode'])
        ->name('shows.createEpisode');
    // Display episode manage page
    Route::get('/shows/{show}/episode/{showEpisode}/manage', [ShowsController::class, 'manageEpisode'])
        ->name('shows.showEpisodes.manageEpisode')
        ->scopeBindings();


// Show Episodes
///////////
    // Shows resource
    Route::resource('showEpisodes', ShowEpisodeController::class);
    // Display episodes index page
    Route::get('/shows/{show}/episodes', [ShowEpisodeController::class, 'index'])
        ->can('viewPremium', 'App\Models\User')
        ->name('showEpisodes');
    // Display episode page
    Route::get('/shows/{show}/episode/{showEpisode}', [ShowEpisodeController::class, 'show'])
        ->can('viewPremium', 'App\Models\User')
        ->name('shows.showEpisodes.show')
        ->scopeBindings();
    // Display episode edit page
    Route::get('/shows/{show}/episode/{showEpisode}/edit', [ShowEpisodeController::class, 'edit'])
//        ->middleware('can:edit,show')
        ->name('shows.showEpisodes.edit')
        ->scopeBindings();
    // Display episode upload page
    Route::get('/shows/{show}/episode/{showEpisode}/upload', [ShowEpisodeController::class, 'upload'])
//        ->middleware('can:edit,show')
        ->name('shows.showEpisodes.upload')
        ->scopeBindings();
    // Update episode
//    Route::get('/shows/{show}/episode/{showEpisode}/edit', [ShowEpisodeController::class, 'update'])
//        ->middleware('can:edit,show')
//        ->name('shows.showEpisodes.update')
//        ->scopeBindings();


    // tec21: This is probably not the best way to do this
    // I'm unable to get the FilePond uploader to show the
    // uploaded image on the shows/edit page after upload.
    // this is my solution. Copy the ImageController.store
    // code to a new ShowController.uploadPoster function.
    Route::post('/showEpisodesUploadPoster', [ShowEpisodesPosterController::class, 'uploadPoster'])
        ->can('viewCreator', 'App\Models\User')
        ->name('showEpisodes.uploadPoster')
    ;

    Route::post('/showsUploadPoster', [ShowsPosterController::class, 'uploadPoster'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.uploadPoster');

    Route::post('/teamsUploadLogo', [TeamsLogoController::class, 'uploadLogo'])
        ->can('viewCreator', 'App\Models\User')
        ->name('teams.uploadLogo');

// Movies
///////////
    Route::resource('movies',MovieController::class);
    // List all movies
    Route::get('/movies/{movie}', [MovieController::class, 'show'])
        ->can('viewVip', 'App\Models\User')
        ->name('movies.show');
    Route::get('/movies', [MovieController::class, 'index'])
        ->can('viewVip', 'App\Models\User')
        ->name('movies');
    // Display movie edit page
    Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])
//        ->middleware('can:edit,movie')
        ->name('movies.edit');
//    // Upload movie
//    Route::post('/movies/upload', [MovieUploadController::class, 'store'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('moviesUpload.store');




// Images + Upload
///////////
    Route::get('/image', [ImageController::class, 'index'])
        ->can('viewCreator', 'App\Models\User')
        ->name('image.index');
    Route::get('/images', [ImageController::class, 'show'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('image.show');

    Route::post('/upload', [ImageController::class, 'store'])
        ->can('viewCreator', 'App\Models\User')
        ->name('image.store');

    Route::get('/upload', function () {
        return redirect('/stream');
    });

// Users
///////////
    // Users resource for admin to create/edit users
    Route::resource('users',UsersController::class);

    // List all users -- this has to be a different controller than the UsersAdminCreateEditController
    // because it uses a different resource class for the search function.
    Route::get('/users', [UsersController::class, 'index'])
        ->can('viewAny', 'App\Models\User')
        ->name('users');

//    // Create a user
    Route::get('/users/create', [UsersController::class, 'create'])
        ->can('create', 'App\Models\User')
        ->name('usersAdminCreateEdit.create');

//    // Add new user to the database
//    Route::post('/users', [UsersController::class, 'store'])->name('users.store');

//    // Show user
    Route::get('/users/{user}', [UsersController::class, 'show'])
        ->can('viewAny', 'App\Models\User')
        ->name('users.show');

//    // Edit user
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])
        ->can('edit', 'App\Models\User')
        ->name('users.edit');

    // Update user
//    Route::put('/users', [UsersController::class, 'update'])->name('users.update');

// Chat
///////////
///
    Route::get('/chat1', function () {
        return view('chat');
    });
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/channels', [ChatController::class, 'channels']);
    Route::get('/chat/channel/{channelId}/messages', [ChatController::class, 'messages']);
    Route::post('/chat/message', [ChatController::class, 'newMessage']);
//    Route::post('/chat/channel/{channelId}/message', function() {
//        ChatMessage::forceCreate(request(['body']));
//    });

// MistAPI
///////////
///

    Route::post('/mistapi', [VideoController::class, 'mistApi'])->name('mistApi');

});
