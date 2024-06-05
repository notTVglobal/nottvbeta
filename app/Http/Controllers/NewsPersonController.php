<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Http\Resources\ImageResource;
use App\Http\Resources\NewsPersonResource;
use App\Http\Resources\NewsStoryResource;
use App\Models\NewsPerson;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsPersonController extends Controller {

  public function fetchNewsPersons(): JsonResponse {
//    // Eager load the 'user' relationship to get the user's name
//    // and the 'roles' relationship to get the roles for each news person.
//    $newsPersons = NewsPerson::with(['user:id,name', 'roles:id,name'])->get();
//    // Transform the newsPersons collection to include role names as an array
//    $newsPersons->transform(function ($newsPerson) {
//      // Map roles relationship to only include role names
//      $newsPerson->roles = $newsPerson->roles->pluck('name');
//
//      return $newsPerson;
//    });
//
//    return response()->json($newsPersons);

    // Eager load the 'user' and 'roles' relationships
    $newsPersons = NewsPerson::with(['user', 'roles'])->get();

    // Transform the newsPersons collection
    $newsPersons->transform(function ($newsPerson) {
      return [
          'id'                 => $newsPerson->id,
          'name'               => $newsPerson->user->name,
          'roles'              => $newsPerson->roles->map(function ($role) {
            return [
                'id'   => $role->id,
                'name' => $role->name,
            ];
          }),
          'profile_photo_path' => $newsPerson->user->profile_photo_path,
          'profile_photo_url'  => $newsPerson->user->profile_photo_url,
      ];
    });

    return response()->json($newsPersons);
  }


  public function assignRole(Request $request, $newsPersonId): \Illuminate\Http\JsonResponse {
    $request->validate([
        'role_id' => 'required|exists:news_people_roles,id',
    ]);

    $newsPerson = NewsPerson::findOrFail($newsPersonId);
    $newsPerson->roles()->syncWithoutDetaching($request->role_id);

    return response()->json(['message' => 'Role assigned successfully!']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Inertia\Response
   */
  public function reportersIndex(): \Inertia\Response {
    $user = Auth::user();
    $canViewNewsroom = optional($user)->can('viewAny', NewsPerson::class);

    // Select the view based on the authentication status
    $component = $user ? 'News/Reporters/Index' : 'LoggedOut/News/Reporters/Index';

    // Filter news people who have a role with id 5 'Reporter'
    $newsPeople = NewsPerson::whereHas('roles', function ($query) {
      $query->where('news_people_roles.id', 5);
    })->with('user')->get()->map(function ($newsPerson) {
      return [
          'id'                 => $newsPerson->user->id,
          'slug'               => $newsPerson->slug,
          'name'               => $newsPerson->user->name,
          'profile_photo_path' => $newsPerson->user->profile_photo_path,
          'profile_photo_url'  => $newsPerson->user->profile_photo_url,
      ];
    });

    return Inertia::render($component, [
        'newsPeople' => $newsPeople,
        'can'        => [
            'viewNewsroom' => $canViewNewsroom
        ]
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param NewsPerson $newsPerson
   * @return \Inertia\Response
   */
  public function reporterShow(NewsPerson $newsPerson): \Inertia\Response {

    $user = Auth::user();
    $canViewNewsroom = optional($user)->can('viewAny', NewsPerson::class);

    // Eager load the image and its appSetting relationship along with roles
    $newsPerson->load(['image.appSetting', 'roles', 'user']);

    // Apply the published scope on the newsStories relationship
    $publishedNewsStories = $newsPerson->newsStories()->with('image.appSetting')->published()->limit(10)->latest()->get();

    $component = $user ? 'News/Reporters/{$id}/Index' : 'LoggedOut/News/Reporters/{$id}/Index';

    return Inertia::render($component, [
        'newsPerson'  => (new NewsPersonResource($newsPerson))->resolve(),
        'newsStories' => NewsStoryResource::collection($publishedNewsStories)->resolve() ?? null,
        'can'         => [
            'viewNewsroom' => $canViewNewsroom
        ]
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return void
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Application|Redirector|RedirectResponse
   */
  public function store(Request $request): Redirector|Application|RedirectResponse {
    $validated = $request->validate([
        'id'         => 'required|exists:users,id', // Ensure the user exists
        'name'       => 'required|string', // Ensure the user exists
        'role_ids'   => 'required|array', // Validate role_ids as an array
        'role_ids.*' => 'exists:news_people_roles,id', // Each role ID must exist
    ]);

    DB::beginTransaction();

    try {
      $newsPerson = $this->createOrRestoreNewsPerson($validated);
      $isUpdating = !$newsPerson->wasRecentlyCreated;
      $this->assignRolesToNewsPerson($newsPerson, $validated['role_ids']);
      $this->sendNotificationForNewsPerson($newsPerson, $validated, $isUpdating);

      DB::commit();

      return back()->with('message', $validated['name'] . ' has successfully been added to the Newsroom.');
    } catch (\Exception $e) {
      DB::rollBack();

      // Log the error message
      Log::error('Error adding user to the Newsroom: ' . $e->getMessage());

      // Return a detailed error message
      return back()->with('error', 'Could not add user to the Newsroom or assign roles. Error: ' . $e->getMessage());
    }
  }

  private function createOrRestoreNewsPerson($validated) {
    $newsPerson = NewsPerson::withTrashed()->where('user_id', $validated['id'])->first();

    if ($newsPerson) {
      return $this->handleExistingNewsPerson($newsPerson, $validated);
    } else {
      return $this->createNewsPerson($validated);
    }
  }

  private function handleExistingNewsPerson($newsPerson, $validated) {
    $wasRecentlyRestored = false;

    if ($newsPerson->trashed()) {
      $this->restoreNewsPerson($newsPerson);
      $wasRecentlyRestored = true;
    }

    // Check if the slug exists, if not generate a unique slug
    if (empty($newsPerson->slug)) {
      $slug = $this->generateUniqueSlug($validated['name']);
      $newsPerson->update(['name' => $validated['name'], 'slug' => $slug]);
    } else {
      $newsPerson->update(['name' => $validated['name']]);
    }

    // Attach the custom property to indicate restoration
    $newsPerson->wasRecentlyRestored = $wasRecentlyRestored;

    // Send notification
    $this->sendNotificationForNewsPerson($newsPerson, $validated, false);

    return $newsPerson;
  }

  private function restoreNewsPerson($newsPerson): void {
    $newsPerson->restore();
  }

  private function createNewsPerson($validated) {
    $slug = $this->generateUniqueSlug($validated['name']);
    $newsPerson = NewsPerson::create([
        'user_id' => $validated['id'],
        'name'    => $validated['name'],
        'slug'    => $slug
    ]);

    // Attach the custom property to indicate restoration
    $newsPerson->wasRecentlyRestored = false;

    // Send notification
    $this->sendNotificationForNewsPerson($newsPerson, $validated, false);

    return $newsPerson;
  }

  private function generateUniqueSlug($name): string {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $counter = 1;

    while (NewsPerson::class::where('slug', $slug)->exists()) {
      $slug = $originalSlug . '-' . $counter;
      $counter++;
    }

    return $slug;
  }

  private function assignRolesToNewsPerson($newsPerson, $roleIds): void {
    $newsPerson->roles()->sync($roleIds);

    foreach ($roleIds as $roleId) {
      $newsPerson->roles()->updateExistingPivot($roleId, ['created_at' => now(), 'updated_at' => now()]);
    }
  }

  private function sendNotificationForNewsPerson($newsPerson, $validated, $isUpdating): void {
    $roleNames = $newsPerson->roles()->whereIn('news_people_roles.id', $validated['role_ids'])->pluck('name')->implode(', ');
    $message = '';
    $name = $validated['name'];

    if ($newsPerson->wasRecentlyRestored) {
      $message = '<span class="text-green-500">' . $name . ' has been restored to the News Team with roles: ' . $roleNames . '.</span>';
    } elseif ($isUpdating) {
      $message = '<span class="text-green-500">' . $name . ' has had their roles updated to: ' . $roleNames . '.</span>';
    } else {
      $message = '<span class="text-green-500">' . $name . ' has been added to the News Team with roles: ' . $roleNames . '.</span>';
    }

    $this->sendNotification(
        $newsPerson->user_id,
        'News Team Update',
        $message
    );
  }

  private function sendNotification($userId, $title, $message): void {
    $notification = new Notification;
    $notification->user_id = $userId;
    $notification->image_id = null; // No image needed
    $notification->url = null; // Set to null or any relevant URL if needed
    $notification->title = $title;
    $notification->message = $message;

    $notification->save();

    event(new NewNotificationEvent($notification));
  }

  public function fetchRoles($id): JsonResponse {
    $newsPerson = NewsPerson::with('roles')->find($id);

    if (!$newsPerson) {
      return response()->json(['message' => 'News Person not found'], 404);
    }

    return response()->json($newsPerson->roles);
  }

  public function updateRoles(Request $request): RedirectResponse {

    $newsPerson = NewsPerson::where('user_id', Auth::id())->first();
    $this->authorize('update', $newsPerson);

    $request->validate([
        'id'         => 'required|exists:news_people,user_id',
        'slug'       => 'required|exists:news_people,slug',
        'role_ids'   => 'required|array',
        'role_ids.*' => 'exists:news_people_roles,id',
    ]);

    $newsPerson = NewsPerson::where('user_id', $request->id)->first();

    if (!$newsPerson) {
      return back()->with('message', 'News Person not found');
//      return response()->json(['message' => 'News Person not found'], 404);
    }

    // Sync roles without detaching to update the roles while maintaining existing ones not included in the request
    $newsPerson->roles()->sync($request->role_ids);

//    return response()->json(['message' => 'Roles updated successfully']);
    return back()->with('message', 'Roles updated successfully');

  }


  /**
   * Display the specified resource.
   *
   * @param NewsPerson $newsPerson
   * @return void
   */
  public function show(NewsPerson $newsPerson) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param NewsPerson $newsPerson
   * @return void
   */
  public function edit(NewsPerson $newsPerson) {
    //
  }

  public function fetchNewsPersonSettings(NewsPerson $newsPerson): JsonResponse {
    $this->authorize('update', $newsPerson);

    // Eager load the image and its appSetting relationship along with roles
    $newsPerson->load(['image.appSetting', 'roles']);

    // Assuming $newsPerson->social_media contains the JSON data with the necessary fields
    return response()->json([
        'image'         => $newsPerson->image ? (new ImageResource($newsPerson->image))->resolve() : null,
        'biography'     => $newsPerson->biography,
        'contact_info'  => $newsPerson->contact_info,
        'other_details' => $newsPerson->other_details,
        'social_media'  => $newsPerson->social_media,
        'roles'         => $newsPerson->roles->map(function ($role) {
          return [
              'id'   => $role->id,
              'name' => $role->name,
          ];
        }),
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param NewsPerson $newsPerson
   * @return RedirectResponse
   * @throws AuthorizationException
   */
  public function update(Request $request, NewsPerson $newsPerson) {
    $this->authorize('update', $newsPerson);

    Log::info('update');

    $validated = $request->validate([
        'biography'              => 'nullable|string',
        'contact_info'           => 'nullable|string',
        'other_details'          => 'nullable|string',
        'social_media.facebook'  => 'nullable|string',
        'social_media.twitter'   => 'nullable|string',
        'social_media.instagram' => 'nullable|string',
        'social_media.linkedin'  => 'nullable|string',
        'social_media.snapchat'  => 'nullable|string',
        'social_media.discord'   => 'nullable|string',
        'social_media.substack'  => 'nullable|string',
    ]);

    $validated['social_media'] = $validated['social_media'] ?? [];

    $newsPerson->update([
        'biography'     => e($validated['biography']),
        'contact_info'  => e($validated['contact_info']),
        'other_details' => e($validated['other_details']),
        'social_media'  => array_map('e', $validated['social_media']),
    ]);

    return back()->with('message', 'NewsPerson updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws AuthorizationException
   */
  public function destroy(HttpRequest $request): RedirectResponse {
    $this->authorize('delete', NewsPerson::class);

    // Retrieve the NewsPerson by user_id
    $newsPerson = NewsPerson::where('user_id', $request->id)->first();

    if (!$newsPerson) {
      return redirect()->route('users.index')->with('error', 'News Person not found.');
    }

    // Get the user's name
    $userName = $newsPerson->user->name;

    // Update the author column in related NewsStories
    $newsPerson->newsStories()->update(['author' => $userName]);

    // Send notification
    $this->sendNotification(
        $newsPerson->user_id,
        'Removed from the News Team',
        '<span class="text-red-500">' . $userName . ' has been removed from the News Team.</span>'
    );

    // Delete the NewsPerson
    $newsPerson->delete();

    return to_route('users.index')->with('warning', 'User Removed From News Team Successfully');
  }

}
