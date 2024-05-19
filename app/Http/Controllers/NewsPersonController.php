<?php

namespace App\Http\Controllers;

use App\Models\NewsPerson;
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
  public function store(Request $request) {
    $validated = $request->validate([
        'id'         => 'required|exists:users,id', // Ensure the user exists
        'name'       => 'required|string', // Ensure the user exists
        'role_ids'   => 'required|array', // Validate role_ids as an array
        'role_ids.*' => 'exists:news_people_roles,id', // Each role ID must exist
    ]);

    DB::beginTransaction();

    try {
      $newsPerson = NewsPerson::firstOrCreate([
          'user_id' => $validated['id'],
      ]);

      // Assign the roles to the NewsPerson
      $newsPerson->roles()->sync($validated['role_ids']);

      // Manually update timestamps for each pivot entry
      foreach ($validated['role_ids'] as $roleId) {
        $newsPerson->roles()->updateExistingPivot($roleId, ['created_at' => now()]);
        $newsPerson->roles()->updateExistingPivot($roleId, ['updated_at' => now()]);
      }

      DB::commit();

      return back()->with('message', $validated['name'] . ' has successfully been added to the Newsroom.');
//        return to_route('users.index')->with('success', 'User has successfully been added to the Newsroom and roles assigned.');
    } catch (Exception $e) {
      DB::rollBack();
      // Log the error or handle it as per your application's requirements
      // Log::error($e->getMessage());
      return back()->with(['error' => 'Could not add user to the Newsroom or assign roles.']);
    }
//        return redirect()->route('newsroom')->with('message', $request->name . ' has successfully been added to the Newsroom.');
//        return to_route('users.index')->with('success', $request->name . ' has successfully been added to the Newsroom.');
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
   * @param $id
   * @return \Inertia\Response
   * @throws AuthorizationException
   */
  public function destroy(HttpRequest $request) {
//        dd($request);
    $this->authorize('delete', NewsPerson::class);
    $id = NewsPerson::query()->where('user_id', $request->id)->pluck('id');
    NewsPerson::destroy($id);

    $user = User::where('id', $request->id);

    return to_route('users.index')->with('warning', 'User Removed From News Team Successfully');


  }
}
