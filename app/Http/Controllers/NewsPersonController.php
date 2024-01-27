<?php

namespace App\Http\Controllers;

use App\Models\NewsPerson;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NewsPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function reportersIndex(): \Inertia\Response {
      $newsPeople = NewsPerson::with('user')->get()->map(function ($newsPerson) {
        return [
            'id' => $newsPerson->user->id,
            'name' => $newsPerson->user->name,
            'profile_photo_path' => $newsPerson->user->profile_photo_path,
            'profile_photo_url' => $newsPerson->user->profile_photo_url,
        ];
      });
      return Inertia::render('News/Reporters/Index', [
          'newsPeople' => $newsPeople,
          'can'         => [
              'viewNewsroom'     => optional(Auth::user())->can('viewAny', NewsPerson::class) ?: false,
          ]
      ]);
  }

  /**
   * Display the specified resource.
   *
   * @param NewsPerson $newsPerson
   * @return \Inertia\Response
   */
  public function reporterShow(NewsPerson $newsPerson): \Inertia\Response
  {

    return Inertia::render('News/Reporters/{$id}/Index', [
        'newsPerson' => [
            'id' => $newsPerson->user->id,
            'name' => $newsPerson->user->name,
            'profile_photo_path' => $newsPerson->user->profile_photo_path,
            'profile_photo_url' => $newsPerson->user->profile_photo_url,
        ],
        'can' => [
            'viewNewsroom' => optional(Auth::user())->can('viewAny', NewsPerson::class) ?: false,
        ]
    ]);
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Routing\Redirector|RedirectResponse
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'id' => 'required',
        ]);
        NewsPerson::create([
            'user_id' => $request->id,
        ]);

//        return redirect()->route('newsroom')->with('message', $request->name . ' has successfully been added to the Newsroom.');
        return to_route('users.index')->with('success', $request->name . ' has successfully been added to the Newsroom.');

    }

    /**
     * Display the specified resource.
     *
     * @param NewsPerson $newsPerson
     * @return void
     */
    public function show(NewsPerson $newsPerson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param NewsPerson $newsPerson
     * @return void
     */
    public function edit(NewsPerson $newsPerson)
    {
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
    public function destroy(HttpRequest $request)
    {
//        dd($request);
        $this->authorize('delete', NewsPerson::class);
        $id = NewsPerson::query()->where('user_id', $request->id)->pluck('id');
        NewsPerson::destroy($id);

        $user = User::where('id', $request->id);

        return to_route('users.index')->with('warning', 'User Removed From News Team Successfully');


    }
}
