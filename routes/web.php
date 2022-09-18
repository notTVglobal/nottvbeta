<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CreatorsController;
use App\Http\Controllers\ShowsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ImageController;
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

    // List all teams
    Route::get('/teams', [TeamsController::class, 'index'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('teams.index');
    // Create a team
    Route::get('/teams/create', [TeamsController::class, 'create'])->name('teams.create');
    // Add new team to database
    Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
    // Single team page
    Route::get('/teams/{team}', [TeamsController::class, 'show'])->name('teams.show');
    // Edit team
    Route::get('/teams/edit/{team}', [TeamsController::class, 'edit'])->name('teams.edit');


    // List all creators
    Route::get('/creators', [CreatorsController::class, 'index'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('creators');
    // Create a creator
    Route::get('/creators/create', [CreatorsController::class, 'create'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('creators.create');

    // List all shows
    Route::get('/shows', [ShowsController::class, 'index'])->name('shows');
    // Create a show
    Route::get('/shows/create', [ShowsController::class, 'create'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.create');
    // Add new show to database
    Route::post('/shows', [ShowsController::class, 'store'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.store');
    // Single show page
    Route::get('/shows/{show}', [ShowsController::class, 'show'])->name('shows.show');
    // Edit show
    Route::get('/shows/edit/{show}', [ShowsController::class, 'edit'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.edit');

    Route::get('/image', [ImageController::class, 'index'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('image.index');
    Route::get('/images', [ImageController::class, 'show'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('image.show');
    Route::post('/upload', [ImageController::class, 'store'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('image.store');

    // Need to move this to a new middleware section for auth_admin
    //
    // List all users
    Route::get('/admin/users', [UsersController::class, 'index'])
        ->can('viewAllUsers', 'App\Models\User')
        ->name('admin.users.index');
    // Create a user
    Route::get('/admin/users/create', [UsersController::class, 'create'])
        ->can('create', 'App\Models\User')
        ->name('admin.users.create');
    // Add new user to the database
    Route::post('/admin/users', [UsersController::class, 'store'])->name('admin.users.store');
    // Show user
    Route::get('/admin/users/{user}', [UsersController::class, 'show'])->name('admin.users.show');
    // Edit user
    Route::get('/admin/users/edit/{user}', [UsersController::class, 'edit'])
        ->can('edit', 'App\Models\User')
        ->name('admin.users.edit');
    // Update user
    Route::put('/admin/users', [UsersController::class, 'update'])->name('admin.users.update');

    // List all channels
    Route::get('/admin/channels', function () {
        return Inertia::render('Admin/Channels/Index');
    })->can('viewAdmin', 'App\Models\User')
        ->name('admin.channels.index');

});
