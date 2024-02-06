<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\MistStreamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MovieUploadController;
use App\Http\Controllers\TestMessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ChannelApiController;

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

// get app_settings is to load first play on the VideoPlayerMain component.
Route::get('/app_settings', [AppSettingController::class, 'getAppSettings']);

//Route::post('/chatTestMessage', '\App\Http\Controllers\TestMessageController@broadcast');

//Route::post('/chatTest', function (){
//    event(new \App\Events\NewChatTestMessage());
//    return ('success');
//});

Route::post('/chatTest', [TestMessageController::class, 'broadcast'])
    ->name('chatTestApi');

Route::get('/products', [ProductController::class, 'index']);

Route::get('/channels_list', [ChannelApiController::class, 'index']);

Route::post('/userValidation', [MistStreamController::class, 'userValidation']);

