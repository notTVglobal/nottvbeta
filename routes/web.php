<?php

use App\Http\Controllers\ImageController;
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
    Route::get('/shows', function () {
        return Inertia::render('Shows');
    })->name('shows');
    Route::get('/image', function () {
        return Inertia::render('Image');
    })->name('image');
//    Route::get('/image', [ImageController::class, 'index'])->name('image');
    Route::get('/images', [ImageController::class, 'show']);
    Route::post('/upload', [ImageController::class, 'store'])->name('image.upload');

    // Need to move this to a new middleware section for auth_admin
    Route::get('/admin/users', function () {
        return Inertia::render('Admin/Users', [
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
    })->name('admin.users');
});
