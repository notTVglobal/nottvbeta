<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
use App\Models\NewsPerson;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
          'id' => $newsPerson->id,
          'name' => $newsPerson->user->name,
          'roles' => $newsPerson->roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
            ];
          }),
          'profile_photo_path' => $newsPerson->user->profile_photo_path,
          'profile_photo_url' => $newsPerson->user->profile_photo_url,
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

    $component = $user ? 'News/Reporters/{$id}/Index' : 'LoggedOut/News/Reporters/{$id}/Index';

    return Inertia::render($component, [
        'newsPerson' => [
            'id'                 => $newsPerson->user->id,
            'name'               => $newsPerson->user->name,
            'profile_photo_path' => $newsPerson->user->profile_photo_path,
            'profile_photo_url'  => $newsPerson->user->profile_photo_url,
        ],
        'can'        => [
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
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Routing\Redirector|RedirectResponse
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
        'id'         => 'required|exists:users,id', // Ensure the user exists
        'name'       => 'required|string', // Ensure the user exists
        'role_ids'   => 'required|array', // Validate role_ids as an array
        'role_ids.*' => 'exists:news_people_roles,id', // Each role ID must exist
    ]);

    DB::beginTransaction();

    try {
      $newsPerson = $this->createOrRestoreNewsPerson($validated);
      $isUpdating = $newsPerson->wasRecentlyCreated ? false : true;
      $this->assignRolesToNewsPerson($newsPerson, $validated['role_ids']);
      $this->sendNotificationForNewsPerson($newsPerson, $validated['name'], $validated['role_ids'], $isUpdating);

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

  private function createOrRestoreNewsPerson($validated)
  {
    $newsPerson = NewsPerson::withTrashed()->where('user_id', $validated['id'])->first();
    $wasRecentlyRestored = false;

    if ($newsPerson) {
      if ($newsPerson->trashed()) {
        $newsPerson->restore();
        $wasRecentlyRestored = true;
      }
      $newsPerson->update(['name' => $validated['name']]);
    } else {
      $newsPerson = NewsPerson::create([
          'user_id' => $validated['id'],
          'name' => $validated['name']
      ]);
    }

    // Attach the custom property to indicate restoration
    $newsPerson->wasRecentlyRestored = $wasRecentlyRestored;

    return $newsPerson;
  }

  private function assignRolesToNewsPerson($newsPerson, $roleIds)
  {
    $newsPerson->roles()->sync($roleIds);

    foreach ($roleIds as $roleId) {
      $newsPerson->roles()->updateExistingPivot($roleId, ['created_at' => now(), 'updated_at' => now()]);
    }
  }

  private function sendNotificationForNewsPerson($newsPerson, $name, $roleIds, $isUpdating)
  {
    $roleNames = $newsPerson->roles()->whereIn('news_people_roles.id', $roleIds)->pluck('name')->implode(', ');
    $message = '';

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

  private function sendNotification($userId, $title, $message)
  {
    $notification = new Notification;
    $notification->user_id = $userId;
    $notification->image_id = null; // No image needed
    $notification->url = null; // Set to null or any relevant URL if needed
    $notification->title = $title;
    $notification->message = $message;

    $notification->save();

    event(new NewNotificationEvent($notification));
  }

  public function fetchRoles($id) {
    $newsPerson = NewsPerson::with('roles')->find($id);

    if (!$newsPerson) {
      return response()->json(['message' => 'News Person not found'], 404);
    }

    return response()->json($newsPerson->roles);
  }

  public function updateRoles(Request $request) {
    $request->validate([
        'id'         => 'required|exists:news_people,user_id',
        'role_ids'   => 'required|array',
        'role_ids.*' => 'exists:news_people_roles,id',
    ]);

    $newsPerson = NewsPerson::find($request->id);

    if (!$newsPerson) {
      return response()->json(['message' => 'News Person not found'], 404);
    }

    // Sync roles without detaching to update the roles while maintaining existing ones not included in the request
    $newsPerson->roles()->sync($request->role_ids);

    return response()->json(['message' => 'Roles updated successfully']);
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

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param NewsPerson $newsPerson
   * @return Response
   */
  public function update(Request $request, NewsPerson $newsPerson) {
    //
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
