<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackMail;
use App\Models\Role;
use App\Models\SubscriptionPlan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Creator;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Mews\Purifier\Facades\Purifier;
use Stripe\Stripe;

class UsersController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index() {
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
        'users'   => User::query()
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'users')
            ->withQueryString()
            ->through(fn($user) => [
                'id'                 => $user->id,
                'name'               => $user->name,
                'email'              => $user->email,
                'profile_photo_path' => $user->profile_photo_path,
                'role'               => role($user->role_id),
                'isAdmin'            => $user->isAdmin,
                'isCreator'          => $user->creator,
                'isNewsPerson'       => $user->newsPerson,
                'isSubscriber'       => $user->subscribed('default'),
                'subscriptionStatus' => $user->subscription('default'),
                'isVip'              => $user->isVip,
                'can'                => [
                    'edit' => Auth::user()->can('edit', $user)
                ],
                'userSelected'       => $user,
            ]),
        'filters' => Request::only(['search']),
        'can'     => [
            'viewAnyUser' => Auth::user()->can('viewAny', User::class),
            'createUser'  => Auth::user()->can('create', User::class),
            'editUser'    => Auth::user()->can('edit', User::class)
        ]
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create() {
    return Inertia::render('Users/Create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param User $user
   * @param Request $request
   * @return Application|RedirectResponse|Redirector
   */
  public function store(User $user, Request $request) {
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
        'name'       => ['required', 'string', 'max:255'],
        'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'password'   => 'string',
        'role_id'    => 'integer',
        'address1'   => ['nullable', 'string', 'max:255'],
        'address2'   => ['nullable', 'string', 'max:255'],
        'city'       => ['nullable', 'string', 'max:255'],
        'province'   => ['nullable', 'string', 'max:255'],
        'country'    => ['nullable', 'string', 'max:255'],
        'postalCode' => ['nullable', 'string', 'max:255'],
        'phone'      => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        'stripe_id'  => ['nullable', 'string', 'max:255'],
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

    $user->loadMissing('newsPerson.roles'); // Eager load the roles of newsPerson if not already loaded

    return Inertia::render('Users/{$id}/Index', [
        'userSelected'       => $user,
        'subscriptionStatus' => $user->subscription('default')->stripe_status ?? null,
        'trialEndsAt'        => $user->subscription('default')->trial_ends_at ?? null,
        'endsAt'             => $user->subscription('default')->ends_at ?? null,
        'subscriptionName'   => $subscriptionName,
        'role'               => role($user->role_id),
        'teams'              => $user->teams,
        'lastLoginAt'        => $user->last_login_at,
        'isNewsPerson'       => $user->relationLoaded('newsPerson') ? !is_null($user->newsPerson) : $user->newsPerson()->exists(),
        'newsPersonRoles'    => $user->newsPerson?->roles->pluck('name')->toArray() ?? [],
        'isCreator'          => $user->relationLoaded('creator') ? !is_null($user->creator) : $user->creator()->exists(),
        'can'                => [
            'viewAnyUser' => Auth::user()->can('viewAny', User::class),
            'createUser'  => Auth::user()->can('create', User::class),
            'editUser'    => Auth::user()->can('edit', User::class)
        ]
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param User $user
   * @return Response
   */
  public function edit(User $user) {
    $isNewsPerson = null;
    if ($user->newsPerson != null) {
      $isNewsPerson = true;
    } else $newsPerson = false;

    return Inertia::render('Users/{$id}/Edit', [
        'userEdit'           => $user,
        'subscriptionStatus' => $user->subscription('default')->stripe_status ?? null,
        'isNewsPerson'       => $isNewsPerson,
        'newsPersonId'       => $user->newsPerson?->id,
        'isVip'              => $user->isVip,
        'hasSubscription'    => $user->subscription() ?? null,
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
  public function update(Request $request, User $user) {

    // validate the request
    $attributes = Request::validate([
        'name'       => ['required', 'string', 'max:255'],
        'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'address1'   => ['nullable', 'string', 'max:255'],
        'address2'   => ['nullable', 'string', 'max:255'],
        'city'       => ['nullable', 'string', 'max:255'],
        'province'   => ['nullable', 'string', 'max:255'],
        'country'    => ['nullable', 'string', 'max:255'],
        'postalCode' => ['nullable', 'string', 'max:255'],
        'phone'      => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        'role_id'    => 'integer',
        'stripe_id'  => ['nullable', 'string', 'max:255'],
    ]);

    // update the user
    $user->update($attributes);

    $userId = $user->id;
    $checkCreator = Creator::where('user_id', $userId)->first();
    // Check if the user is already on the creator table
    if (!$checkCreator && $attributes['role_id'] == 4) {
      // if no, and the new role_id IS a creator
      // add the user to the Creator table.
      Creator::create([
          'user_id' => $userId,
      ]);

    } // Check if the user is already on the creator table
    elseif ($checkCreator == !null && $attributes['role_id'] != 4) {
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
        'role'         => role($user->role_id),
        'teams'        => $user->teams,
        'can'          => [
            'viewAnyUser' => Auth::user()->can('viewAny', User::class),
            'createUser'  => Auth::user()->can('create', User::class),
            'editUser'    => Auth::user()->can('edit', User::class)
        ]
    ])->with('message', $user->name . ' updated successfully.');
  }




  // This is the user update contact information method
  // for the user's settings page.
  //
  public function updateContact(HttpRequest $request, User $user) {
    // Validate the request
    $validatedData = $request->validate([
        'address1'   => ['nullable', 'string', 'max:255'],
        'address2'   => ['nullable', 'string', 'max:255'],
        'city'       => ['nullable', 'string', 'max:255'],
        'province'   => ['nullable', 'string', 'max:255'],
        'country'    => ['nullable', 'string', 'max:255'],
        'postalCode' => ['nullable', 'string', 'max:255'],
        'phone'      => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
    ]);

    // Find the user
    $user = User::find($request->id);
    if (!$user) {
      return redirect()->back()->with('error', 'User not found.');
    }

    // Sanitize data
    $sanitizedData = [];
    foreach ($validatedData as $key => $value) {
      $sanitizedData[$key] = Purifier::clean($value);
    }

    // Update the user with sanitized data
    $user->fill($sanitizedData);
    $user->save();

    // Redirect with success message
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


  public function getUserStoreData(): bool|string {
    $user = auth()->user();

    return json_encode([
        'id'                    => $user->id,
        'isAdmin'               => $user->isAdmin(),
        'isCreator'             => $user->isCreator(),
        'isNewsPerson'          => $user->isNewsPerson(),
        'isVip'                 => $user->isVip(),
        'isSubscriber'          => $user->subscribed('default'),
        'subscriptionActive'    => optional($user->subscription('default'))->active(),
        'hasAccount'            => $user->hasAccount(),
        'hasConsentedToCookies' => $user->hasConsentedToCookies(),
    ]);
  }

  public function getUserData(Request $request): \Illuminate\Http\JsonResponse {
    $user = Auth::user();

    if ($user) {
      return response()->json([
          'name' => $user->name,
          'email' => $user->email,
          'country' => $user->country,
          'postalCode' => $user->postalCode,
      ]);
    }

    return response()->json(null, 401); // Unauthorized response if user is not authenticated
  }

  public function updateTimezone(HttpRequest $request): \Illuminate\Http\JsonResponse {
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

  public function vipRemove(HttpRequest $request) {
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
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    //
  }


  public function submitFeedback(HttpRequest $request) {
//
//    $validated = $request->validate([
//        'message' => 'required|string',
//        'name' => 'nullable|string',
//        'email' => 'string',
//        'phone' => 'string',
//        'city' => 'string',
//        'province' => 'string',
//      ]);


    $validatedData = $request->validate([
        'message'    => 'required|string',
        'screenshot' => 'nullable|image|max:5000', // Allow only images up to 5MB
    ]);

//    if ($request->hasFile('screenshot')) {
//      $filePath = $request->file('screenshot')->store('feedbackAttachments', 'public');
//      $fileUrl = Storage::disk('local')->url($filePath);
//      Log::debug('File Path:', ['path' => $filePath]);
//      Log::debug('File URL:', ['url' => $fileUrl]);
//    }

//    if ($request->hasFile('screenshot') && $request->file('screenshot')->isValid()) {
//      // Proceed with storing the file
//      $filePath = $request->file('screenshot')->store('feedbackAttachments', 'public');
//      $fileUrl = Storage::disk('local')->put('$filePath')->url($filePath);
//    } else {
//      // Handle the error, file not uploaded correctly
//    }


//    $filePath = null;
//    if ($request->hasFile('screenshot')) {
//      $filePath = $request->file('screenshot')->store('public/screenshots');
//      // Generate a URL to the stored file
//      $fileUrl = Storage::disk('local')->url('/feedbackAttachments/'.$filePath);
//      $fileUrl = Storage::disk('local')->url('/feedbackAttachments/'.$filePath);
//    }

    $user = auth()->user();

//    $screenshotPath = null;
//    if ($request->hasFile('screenshot')) {
//      $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
//    }

    $sanitizedMessage = Purifier::clean($validatedData['message']);

    // add Message to log:
    Log::notice("Orange Feedback Form Submission" . "\n*Message*: `" . $sanitizedMessage . "`\n*Name*: `" . ($user->name ?? 'N/A') . "`");

    // Send the email
    try {
      Mail::to('hello@not.tv')->send(new FeedbackMail([
          'message'  => $sanitizedMessage,
          'name'     => $user->name ?? '',
          'email'    => $user->email ?? '',
          'phone'    => $user->phone ?? '',
          'city'     => $user->city ?? '',
          'province' => $user->province ?? '',
          'url'      => $fileUrl ?? '',
        /* other data */
      ]));
    } catch (\Exception $e) {
      Log::error('Mail sending error: ' . $e->getMessage());
      $request->session()->flash('feedback', 'There was an error submitting the feedback form. Please let Travis or Cathy know.');
      // Optionally return an error response
    }
    // Flash a message to the session
    $request->session()->flash('feedback', 'Feedback submitted successfully!');

    // Redirect back or to another relevant page
    return redirect()->back();
  }

  public function search(HttpRequest $request) {
    // Validate the input
    $validatedData = Validator::make($request->all(), [
        'query' => 'required|string|max:255',
    ])->validate();

    // Sanitize the input
    $query = e($validatedData['query']);

    $users = User::where('name', 'LIKE', "%{$query}%")
        ->orWhere('email', 'LIKE', "%{$query}%")
        ->get(['id', 'name', 'email']); // Only return necessary fields

    return response()->json($users);
  }

  public function consentCookies(HttpRequest $request): \Illuminate\Http\JsonResponse {
    // Ensure the user is authenticated
    $user = Auth::user();
    if (!$user) {
      return response()->json(['message' => 'User not authenticated.'], 401);
    }

    // Check if the user has already consented
    if ($user->hasConsentedToCookies()) {
      return response()->json(['message' => 'User has already consented to cookies.'], 200);
    }

    // Set the cookie consent
    $user->setCookieConsent();

    return response()->json(['message' => 'Cookie consent recorded.'], 200);
  }

  public function checkCookieConsent(HttpRequest $request): \Illuminate\Http\JsonResponse
  {
    $user = Auth::user();
    if (!$user) {
      return response()->json(['message' => 'User not authenticated.'], 401);
    }

    $hasConsented = $user->hasConsentedToCookies();

    return response()->json(['hasConsented' => $hasConsented], 200);
  }



}
