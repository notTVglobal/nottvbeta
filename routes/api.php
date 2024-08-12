<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\MistServerController;
use App\Http\Controllers\MistStreamController;
use App\Http\Controllers\MistTriggerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SearchController;
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

Route::middleware('auth:sanctum')->group(function () {
  // Routes that require authentication
  Route::post('/chatTestMessage', [TestMessageController::class, 'broadcast']);

  Route::get('/products', [ProductController::class, 'index']);

});

// get app_settings is to load first play on the VideoPlayerMain component.
// TODO: Delete this if nothing breaks. It's a security risk.
//Route::get('/app_settings', [AppSettingController::class, 'getAppSettings']);



//Route::post('/chatTestMessage', '\App\Http\Controllers\TestMessageController@broadcast');

//Route::post('/chatTest', function (){
//    event(new \App\Events\NewChatTestMessage());
//    return ('success');
//});

//Route::post('/chatTest', [TestMessageController::class, 'broadcast'])
//    ->name('chatTestApi');



// this api endpoint was built for testing purposes.
//Route::post('/mist-trigger/trigger', [MistTriggerController::class, 'handleTrigger']);

// our first mist server trigger... for Access Control
Route::post('/mist-trigger/validate-user', [MistTriggerController::class, 'handleValidateUser']);

Route::post('/mist-trigger/push-out-start', [MistTriggerController::class, 'handlePushOutStart']);
Route::post('/mist-trigger/push-end', [MistTriggerController::class, 'handlePushEnd']);
Route::post('/mist-trigger/recording-end', [MistTriggerController::class, 'handleRecordingEnd']);

Route::post('/mist-trigger/log', [MistTriggerController::class, 'logTrigger']);
//Route::post('/mist-trigger/mist-push-handler', [MistTriggerController::class, 'handleMistPush']);


Route::get('/news-countries-simple-list', [NewsController::class, 'getNewsCountriesSimpleList'])->name('news.countries.simpleList');

// Search ShowEpisodes with Team or Show model
Route::get('/search/{modelType}/{id}/episodes', [SearchController::class, 'searchShowEpisodes'])->name('model.search');
