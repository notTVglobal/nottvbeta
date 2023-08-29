<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MovieUploadController;
use App\Http\Controllers\TestMessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\CurrentViewersController;
use App\Http\Controllers\Api\ChannelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('messages', [ChatController::class, 'message']);
//Route::get('prometheus', 'http://mist.nottv.io:4242/nottv');

Route::post('movies/upload', [MovieUploadController::class, 'upload'])
    ->name('moviesApi.upload');

Route::post('/mistTrigger', [\App\Http\Controllers\mistTriggerController::class, 'logTrigger'])->name('mistTrigger.logTrigger');

Route::post('/chatTestMessage', [TestMessageController::class, 'broadcast']);
//Route::post('/chatTestMessage', '\App\Http\Controllers\TestMessageController@broadcast');

//Route::post('/chatTest', function (){
//    event(new \App\Events\NewChatTestMessage());
//    return ('success');
//});

Route::post('/chatTest', [TestMessageController::class, 'broadcast'])
    ->name('chatTestApi');

Route::get('/products', [ProductController::class, 'index']);

Route::post('/getCurrentViewers', [CurrentViewersController::class, 'getCurrentViewers']);
Route::post('/addCurrentViewer', [CurrentViewersController::class, 'addCurrentViewer']);
Route::post('/removeCurrentViewer', [CurrentViewersController::class, 'removeCurrentViewer']);

Route::get('/channel_list', [ChannelController::class, 'index']);

