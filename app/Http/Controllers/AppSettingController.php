<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AppSettingController extends Controller {

  public function getAppSettings() {
    return AppSetting::with('channel:name')
        ->get();
  }

  public function serveFirstPlayData() {
    $path = storage_path('app/json/firstPlayData.json');

    if (!file_exists($path)) {
      return response()->json(['error' => 'File not found.'], 404);
    }

    $content = file_get_contents($path);
    $data = json_decode($content, true);

    return response()->json($data);
  }

  public function getRtmpUri() {
    $rtmpUri = AppSetting::query()->value('mist_server_rtmp_uri');
    return response($rtmpUri, 200)->header('Content-Type', 'text/plain');
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Models\AppSetting $appSetting
   * @return \Illuminate\Http\Response
   */
  public function show(AppSetting $appSetting) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\AppSetting $appSetting
   * @return \Illuminate\Http\Response
   */
  public function edit(AppSetting $appSetting) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\AppSetting $appSetting
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, AppSetting $appSetting) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\AppSetting $appSetting
   * @return \Illuminate\Http\Response
   */
  public function destroy(AppSetting $appSetting) {
    //
  }
}
