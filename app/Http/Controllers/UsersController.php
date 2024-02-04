<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SubscriptionPlan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Creator;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Stripe\Stripe;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        function role($roleId) {
            $role = Role::query()->where('id', $roleId)->first();
            return $role->role;
        }
//        $teams = User::find(1)->teams()->orderBy('name')->get();
//        dd($teams);

//        foreach ($user->teams as $team) {
//            return $team->pivot->created_at;
//        }

        return Inertia::render('Users/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10, ['*'], 'users')
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo_path' => $user->profile_photo_path,
                    'role' => role($user->role_id),
                    'isAdmin' => $user->isAdmin,
                    'isCreator' => $user->creator,
                    'isNewsPerson' => $user->newsPerson,
                    'isSubscriber' => $user->subscribed('default'),
                    'subscriptionStatus' => $user->subscription('default'),
                    'isVip' => $user->isVip,
                    'can' => [
                        'edit' => Auth::user()->can('edit', $user)
                    ],
                    'userSelected' => $user,
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewAnyUser' => Auth::user()->can('viewAny', User::class),
                'createUser' => Auth::user()->can('create', User::class),
                'editUser' => Auth::user()->can('edit', User::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(User $user, Request $request)
    {
//        Validator::make($request, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => $this->passwordRules(),
//        ])->validate();
//
//        return User::create([
//            'name' => $request['name'],
//            'email' => $request['email'],
//            'password' => Hash::make($request['password']),
//            'role_id' => '2',
//            'address1' => null,
//            'address2' => null,
//            'city' => null,
//            'province' => null,
//            'country' => null,
//            'postalCode' => null,
//            'phone' => null,
//            'creatorNumber' => null,
//            'subscriptionStatus' => null,
//        ]);

        $attributes = Request::validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'string',
            'role_id' => 'integer',
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postalCode' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'stripe_id' => ['nullable', 'string', 'max:255'],
        ]);

        // create the user
        User::create($attributes);

        // Add user to Creators table, if the user has a creator role_id
        if ($attributes['role_id'] == 4) {
            $newUserName = $attributes['name'];
            $newUser = User::query()->where('name', $newUserName)->firstOrFail();
            Creator::create([
                'user_id' => $newUser->id,
            ]);
        }

        // redirect
        return redirect('/users')->with('success', 'User Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response {
        function role($roleId) {
            $role = Role::query()->where('id', $roleId)->first();
            return $role->role;
        }

//        dd($user->subscription());
        // get Subscription where subscription_plan matches price_id, return subscription name

        $priceId = $user->subscription()->stripe_price ?? null;
        $subscriptionPlan = SubscriptionPlan::where('price_id', '=', $priceId)->first();
        $subscriptionName = $subscriptionPlan->name ?? null;

        return Inertia::render('Users/{$id}/Index', [
            'userSelected' => $user,
            'subscriptionStatus' => $user->subscription('default')->stripe_status ?? null,
            'trialEndsAt' => $user->subscription('default')->trial_ends_at ?? null,
            'endsAt' => $user->subscription('default')->ends_at ?? null,
            'subscriptionName' => $subscriptionName,
            'role' => role($user->role_id),
            'teams' => $user->teams,
            'can' => [
                'viewAnyUser' => Auth::user()->can('viewAny', User::class),
                'createUser' => Auth::user()->can('create', User::class),
                'editUser' => Auth::user()->can('edit', User::class)
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $isNewsPerson = null;
        if ($user->newsPerson != null) {
            $isNewsPerson = true;
        } else $newsPerson = false;

        return Inertia::render('Users/{$id}/Edit', [
            'userEdit' => $user,
            'subscriptionStatus' => $user->subscription('default')->stripe_status ?? null,
            'isNewsPerson' => $isNewsPerson,
            'isVip' => $user->isVip,
            'hasSubscription' => $user->subscription() ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */

    // NOTE: This is how the Admin updates the user details.
    // this is probably better moved to a different controller.
    // tec21: I was tring to use the same action controller that
    // Fortify/Jetstream built to update user profile information.
    // to no avail. The new user settings updateContactInformationForm
    // update method is below this first update method.
    //
    public function update(Request $request, User $user)
    {

        // validate the request
        $attributes = Request::validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postalCode' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'role_id' => 'integer',
            'stripe_id' => ['nullable', 'string', 'max:255'],
        ]);

        // update the user
        $user->update($attributes);
        sleep(1);

        $userId = $user->id;
        $checkCreator = Creator::where('user_id', $userId)->first();
        // Check if the user is already on the creator table
        if(!$checkCreator && $attributes['role_id'] == 4){
            // if no, and the new role_id IS a creator
            // add the user to the Creator table.
            Creator::create([
                'user_id' => $userId,
            ]);

        }
        // Check if the user is already on the creator table
        elseif ($checkCreator == !null && $attributes['role_id'] != 4 ) {
            // if yes, and new role_id is NOT a creator, remove the user
            // from the creator table
            Creator::destroy($checkCreator->id);
        }

        // redirect
        function role($roleId) {
            $role = Role::query()->where('id', $roleId)->first();
            return $role->role;
        }

//        return Inertia::render('Users/{$id}/Index', [
//            'userSelected' => $user,
//            'role' => role($user->role_id),
//            'teams' => $user->teams,
//            'can' => [
//                'viewAnyUser' => Auth::user()->can('viewAny', User::class),
//                'createUser' => Auth::user()->can('create', User::class),
//                'editUser' => Auth::user()->can('edit', User::class)
//            ]
//        ])->with('message', 'User Updated Successfully');

        return Redirect::route('users.index', [
            'userSelected' => $user,
            'role' => role($user->role_id),
            'teams' => $user->teams,
            'can' => [
                'viewAnyUser' => Auth::user()->can('viewAny', User::class),
                'createUser' => Auth::user()->can('create', User::class),
                'editUser' => Auth::user()->can('edit', User::class)
            ]
        ])->with('message', $user->name . ' updated successfully.');
    }




    // This is the user update contact information method
    // for the user's settings page.
    //
    public function updateContact(HttpRequest $request, User $user)
    {

        $id = $request->id;
        $user = User::find($id);
        // validate the request
        $request->validate([
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postalCode' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);

        // update the user
        $user->address1 = $request->address1;
        $user->address2 = $request->address2;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->country = $request->country;
        $user->postalCode = $request->postalCode;
        $user->phone = $request->phone;
        $user->save();
        sleep(1);

        // redirect
        return redirect(route('profile.show'))->with('message', 'Contact Info Updated Successfully');
//        return Inertia::render('Settings')->with('message', 'Contact Info Updated Successfully');



//        // validate the request
//        $attributes = Request::validate([
//            'address1' => ['nullable', 'string', 'max:255'],
//            'address2' => ['nullable', 'string', 'max:255'],
//            'city' => ['nullable', 'string', 'max:255'],
//            'province' => ['nullable', 'string', 'max:255'],
//            'country' => ['nullable', 'string', 'max:255'],
//            'postalCode' => ['nullable', 'string', 'max:255'],
//            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
//        ]);

//        // update the user
//        $user->update($attributes);
//        sleep(1);
    }

    public function getUserStoreData()
    {
        if (auth()->user()->creator) {
            $creator = true;
        } else $creator = false;

        if (auth()->user()->stripe_id) {
            $hasAccount = true;
        } else $hasAccount = false;

        if (auth()->user()->isAdmin) {
            $isAdmin = true;
        } else $isAdmin = false;

        if (auth()->user()->newsPerson) {
            $isNewsPerson = true;
        } else $isNewsPerson = false;

        if (auth()->user()->isVip) {
            $isVip = true;
        } else $isVip = false;

        return json_encode([
            'id' => auth()->user()->id,
            'isAdmin' => $isAdmin,
            'isCreator' => $creator,
            'isNewsPerson' => $isNewsPerson,
            'isVip' => $isVip,
            'isSubscriber' => auth()->user()->subscribed('default'),
            'hasAccount' => $hasAccount,
        ]);
    }

    public function updateTimezone(HttpRequest $request)
    {
        // Validate the request data (timezone)
        $request->validate([
            'timezone' => 'required|string', // You can add more validation rules if needed
        ]);

        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Update the user's timezone
        $user->timezone = $request->input('timezone');
        $user->save();

        return response()->json(['message' => 'Timezone updated successfully']);
    }

    public function vipAdd(HttpRequest $request) {
      if (!auth()->user()->isAdmin) {
        return to_route('users.index')->with('message', 'You are not authorized to perform this action.');
      }
            $user = User::find($request->id);
            $user->isVip = true;
            $user->update();

//        return redirect()->route('newsroom')->with('message', $request->name . ' has successfully been added to the Newsroom.');
//        return with('message', 'You are not authorized to perform this action.');
        return to_route('users.index')->with('message', $user->name . ' has successfully been added to VIP.');

    }

    public function vipRemove(HttpRequest $request)
    {
      if (!auth()->user()->isAdmin) {
        return to_route('users.index')->with('message', 'You are not authorized to perform this action.');
      }

        $user = User::find($request->id);
        $user->isVip = false;
        $user->update();

        return to_route('users.index')->with('message', $user->name . ' removed from VIP.');


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
