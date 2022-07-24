<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ShowsController;
use App\Models\Show;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;


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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/training', function () {
        return Inertia::render('Training');
    })->name('training');
    Route::get('/stream', function () {
        return Inertia::render('Stream');
    })->name('stream');
    Route::get('/video', function () {
        return Inertia::render('Video');
    })->name('video');
    Route::get('/video2', function () {
        return Inertia::render('Video2');
    })->name('video2');


    // List all shows
    Route::get('/shows', [ShowsController::class, 'index'])->name('shows');








    //     Create a show
    Route::get('/shows/create', [ShowsController::class, 'create'])->name('shows.create');
    // Single show page
    Route::get('/shows/{show}', [ShowsController::class, 'show'])->name('shows.show');






    Route::get('/image', function () {
        return Inertia::render('Image');
    })->name('image');
//    Route::get('/image', [ImageController::class, 'index'])->name('image');
    Route::get('/images', [ImageController::class, 'show']);
    Route::post('/upload', [ImageController::class, 'store'])->name('image.upload');

    // Need to move this to a new middleware section for auth_admin
    Route::get('/admin/users', function () {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name
            ]),
            'filters' => Request::only(['search'])
        ]);
    })->name('admin.users.index');
    Route::get('/admin/users/create', function() {
        return Inertia::render('Admin/Users/Create');
    })->name('admin.users.create');
    Route::post('/admin/users', function() {
        // validate the request
        $attributes = Request::validate([
           'name' => 'required',
           'email' => ['required', 'email'],
           'password' => ['required', 'min:8'],
        ]);
        // create the user
        User::create($attributes);
        // redirect
        return redirect('/admin/users');
    });
    Route::post('/admin/users/edit', function($userId) {
        return Inertia::render('Admin/Users/Edit/{id}' . ${$userId});
    })->name('admin.users.edit');
    Route::patch('/admin/users', function() {
        // validate the request
        $attributes = Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);
        // update the user
        User::where("userId", $id)-> update($attributes);
        // redirect
        return redirect('/admin/users');
    });
});
