<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CreatorsController;
use App\Http\Controllers\ShowsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersAdminCreateEditController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PostController;
//use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Middleware\PusherEvent;


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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->can('viewCreator', 'App\Models\User')
        ->name('dashboard');

    Route::get('/training', function () {
        return Inertia::render('Training');
    })->can('viewCreator', 'App\Models\User')
        ->name('training');

    Route::get('/stream', function () {
        return Inertia::render('Stream');
    })->name('stream');

    Route::get('/upgrade', function () {
        return Inertia::render('Upgrade');
    })->name('upgrade');

//    Route::get('/posts', function () {
//        return Inertia::render('Posts/Index');
//    })->can('viewPremium', 'App\Models\User')
//        ->name('posts');

    Route::resource('posts',PostController::class);

    Route::get('/posts', [PostController::class, 'index'])
        ->can('viewPremium', 'App\Models\User')
        ->name('posts');

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

    Route::get('/channels', function () {
        return Inertia::render('Channels');
    })->can('viewPremium', 'App\Models\User')
        ->name('channels');

    Route::get('/movies', function () {
        return Inertia::render('Movies');
    })->can('viewPremium', 'App\Models\User')
        ->name('movies');

    Route::get('/video', function () {
        return Inertia::render('Video');
    })->can('viewAdmin', 'App\Models\User')
        ->name('video');

    Route::get('/shop', function () {
        return Inertia::render('Shop');
    })->name('shop');

    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule');

    Route::get('/golive', function () {
        return Inertia::render('GoLive');
    })->can('viewCreator', 'App\Models\User')
        ->name('golive');

    // temp page to test Stores
    Route::get('/quiz', function () {
        return Inertia::render('QuizHome');
    })->can('viewAdmin', 'App\Models\User')
        ->name('quiz');


    Route::resource('teams',TeamsController::class);
    // List all teams
    Route::get('/teams', [TeamsController::class, 'index'])
        ->middleware('can:viewAny,App\Models\User')
        ->name('teams.index');
    // Create a team
    Route::get('/teams/create', [TeamsController::class, 'create'])
        ->can('viewCreator', 'App\Models\User')
        ->name('teams.create');
    // Add new team to database
//    Route::post('/teams', [TeamsController::class, 'store'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('teams.store');
    // Display teams manage page
    Route::get('/teams/{team}/manage', [TeamsController::class, 'manage'])
        ->middleware('can:manage,team')
        ->name('teams.manage');
    // Edit team
    Route::get('/teams/{team}/edit', [TeamsController::class, 'edit'])
        ->middleware('can:edit,team')
        ->name('teams.edit');


    // Creators resource
    Route::resource('creators',CreatorsController::class);
    // Display creator page
    Route::get('/creators/{creator}', [CreatorsController::class, 'show'])
        ->name('creators.show');

    // Shows resource
    Route::resource('shows',ShowsController::class);
    // Display shows index page
    Route::get('/shows', [ShowsController::class, 'index'])
        ->can('viewPremium', 'App\Models\User')
        ->name('shows');
    // Display shows manage page
    Route::get('/shows/{show}/manage', [ShowsController::class, 'manage'])
        ->middleware('can:manage,show')
        ->name('shows.manage');
    // Display shows edit page
    Route::get('/shows/{show}/edit', [ShowsController::class, 'edit'])
        ->middleware('can:edit,show')
        ->name('shows.edit');
    // Display shows create page
    Route::get('/shows/create', [ShowsController::class, 'create'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.create');

    // tec21: This is probably not the best way to do this
    // I'm unable to get the FilePond uploader to show the
    // uploaded image on the shows/edit page after upload.
    // this is my solution. Copy the ImageController.store
    // code to a new ShowController.uploadPoster function.
    Route::post('/showsUploadPoster', [ShowsController::class, 'uploadPoster'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.uploadPoster');

    // List all shows
//    Route::get('/shows', [ShowsController::class, 'index'])->name('shows');
    // Create a show
//    Route::get('/shows/create', [ShowsController::class, 'create'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('shows.create');
    // Add new show to database
//    Route::post('/shows', [ShowsController::class, 'store'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('shows.store');
    // Single show page
//    Route::get('/shows/{show}', [ShowsController::class, 'show'])->name('shows.show');
    // Edit show
//    Route::get('/shows/edit/{show}', [ShowsController::class, 'edit'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('shows.edit');



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

    //    // Manage user
    Route::get('/users/{user}', [UsersController::class, 'manage'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('users.manage');

//    // Edit user
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])
        ->can('edit', 'App\Models\User')
        ->name('users.edit');

    // Update user
//    Route::put('/users', [UsersController::class, 'update'])->name('users.update');



    // List all channels
    Route::get('/admin/channels', function () {
        return Inertia::render('Admin/Channels/Index');
    })->can('viewAdmin', 'App\Models\User')
        ->name('admin.channels.index');

});
