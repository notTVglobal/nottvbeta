<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Creator;
use App\Models\NewsPerson;
use App\Models\Shop;
use App\Models\Show;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// A note for testing the shop locally. Use https://expose.dev

class ShopController extends Controller {

  public function getFavouriteOptions(HttpRequest $request): \Illuminate\Http\JsonResponse {
    $type = $request->query('type');

    $options = [];
    switch ($type) {
      case 'creator':
        $options = Creator::with('user')
            ->whereJsonContains('settings', ['profile_is_public' => true])
            ->get()
            ->map(function ($creator) {
              return [
                  'id'                 => $creator->id,
                  'name'               => $creator->user->name,
                  'profile_photo_path' => $creator->user->profile_photo_path,
                  'profile_photo_url'  => $creator->user->profile_photo_url,
              ];
            });
        break;
      case 'show':
        $options = Show::with('image.appSetting')
            ->whereIn('show_status_id', [1, 2])
            ->get()
            ->map(function ($show) {
              return [
                  'id'    => $show->id,
                  'name'  => $show->name,
                  'image' => $show->image ? (new ImageResource($show->image))->resolve() : null,
              ];
            });
        break;
      case 'reporter':
        $options = NewsPerson::with('user')
            ->whereHas('roles', function ($query) {
              $query->where('role_id', 5); // Assuming '5' is the role id for reporters
            })
            ->get()
            ->map(function ($newsPerson) {
              return [
                  'id' => $newsPerson->id,
                  'name' => $newsPerson->user->name,
                  'profile_photo_path' => $newsPerson->user->profile_photo_path,
                  'profile_photo_url'  => $newsPerson->user->profile_photo_url,
              ];
            });
        break;
    }

    return response()->json($options);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function purchase() {
    //
  }

  public function summary(HttpRequest $request) {
    // get order from the database.
    return Inertia::render('Shop/Summary', [
        'order' => $request['order'],
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index() {
    return Inertia::render('Shop/Index');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function checkout() {
    return Inertia::render('Shop/Checkout', [
    ]);
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
   * @param \App\Models\Shop $shop
   * @return \Illuminate\Http\Response
   */
  public function show(Shop $shop) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\Shop $shop
   * @return \Illuminate\Http\Response
   */
  public function edit(Shop $shop) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\Shop $shop
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Shop $shop) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\Shop $shop
   * @return \Illuminate\Http\Response
   */
  public function destroy(Shop $shop) {
    //
  }
}
