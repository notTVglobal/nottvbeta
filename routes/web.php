<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CreatorsController;
use App\Http\Controllers\ShowsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TeamsController;
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
    return Inertia::render('TermsOfService');
})->name('terms');

Route::get('/privacy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/stream', function () {
        return Inertia::render('Stream');
    })->name('stream');
    Route::get('/posts', function () {
        return Inertia::render('Posts');
    })->name('posts');
    Route::get('/channels', function () {
        return Inertia::render('Channels');
    })->name('channels');
    Route::get('/movies', function () {
        return Inertia::render('Movies');
    })->name('movies');
    Route::get('/video', function () {
        return Inertia::render('Video');
    })->name('video');
    Route::get('/shop', function () {
        return Inertia::render('Shop');
    })->name('shop');
    Route::get('/schedule', function () {
        return Inertia::render('Schedule');
    })->name('schedule');
    Route::get('/training', function () {
        return Inertia::render('Training');
    })->name('training');

    // temp page to test Stores
    Route::get('/quiz', function () {
        return Inertia::render('QuizHome');
    })->name('quiz');

    // List all teams
    Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
    // Create a team
    Route::get('/teams/create', [TeamsController::class, 'create'])->name('teams.create');
    // Add new team to database
    Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
    // Single team page
    Route::get('/teams/{team}', [TeamsController::class, 'show'])->name('teams.show');
    // Edit team
    Route::get('/teams/edit/{team}', [TeamsController::class, 'edit'])->name('teams.edit');


    // List all creators
    Route::get('/creators', [CreatorsController::class, 'index'])->name('creators');
    // Create a creator
    Route::get('/creators/create', [CreatorsController::class, 'create'])->name('creators.create');

    // List all shows
    Route::get('/shows', [ShowsController::class, 'index'])->name('shows');
    // Create a show
    Route::get('/shows/create', [ShowsController::class, 'create'])->name('shows.create');
    // Add new show to database
    Route::post('/shows', [ShowsController::class, 'store'])->name('shows.store');
    // Single show page
    Route::get('/shows/{show}', [ShowsController::class, 'show'])->name('shows.show');
    // Edit show
    Route::get('/shows/edit/{show}', [ShowsController::class, 'edit'])->name('shows.edit');


    Route::get('/image', function () {
        return Inertia::render('Image');
    })->name('image');
//    Route::get('/image', [ImageController::class, 'index'])->name('image');
    Route::get('/images', [ImageController::class, 'show']);
    Route::post('/upload', [ImageController::class, 'store'])->name('image.upload');

    // Need to move this to a new middleware section for auth_admin
    //
    // List all users
    Route::get('/admin/users', [UsersController::class, 'index'])->name('admin.users.index');
    // Create a user
    Route::get('/admin/users/create', [UsersController::class, 'create'])->name('admin.users.create');
    // Add new user to the database
    Route::post('/admin/users', [UsersController::class, 'store'])->name('admin.users.store');
    // Show user
    Route::get('/admin/users/{user}', [UsersController::class, 'show'])->name('admin.users.show');
    // Edit user
    Route::get('/admin/users/edit/{user}', [UsersController::class, 'edit'])->name('admin.users.edit');
    // Update user
    Route::put('/admin/users', [UsersController::class, 'update'])->name('admin.users.update');

    // List all channels
    Route::get('/admin/channels', function () {
        return Inertia::render('Admin/Channels/Index');
    })->name('admin.channels.index');

});
