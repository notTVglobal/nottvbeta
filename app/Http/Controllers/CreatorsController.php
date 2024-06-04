<?php

namespace App\Http\Controllers;


use App\Actions\Fortify\PasswordValidationRules;
use App\Events\CreatorRegistrationCompleted;
use App\Http\Resources\CreatorResource;
use App\Http\Resources\NewsStoryResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamResourceWithShows;
use App\Mail\InviteExistingCreatorMail;
use App\Models\Creator;
use App\Models\InviteCode;
use App\Models\NewsCountry;
use App\Mail\InviteCreatorMail;
use App\Actions\Fortify\CreateNewCreator;
use App\Models\Team;
use App\Models\User;
use App\Rules\UnclaimedInviteCode;
use App\Services\NotificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

use Inertia\Response;
use App\Services\InviteCodeService;
use Laravel\Jetstream\Jetstream;

class CreatorsController extends Controller {

  use PasswordValidationRules;

  protected InviteCodeService $inviteCodeService;

  public function __construct(InviteCodeService $inviteCodeService) {
    $this->inviteCodeService = $inviteCodeService;
  }

  public function invite(): Response {
    if (Auth::check()) {
      // User is logged in
      $user = auth()->user();
      // Load the user with createdInviteCodes relationship
      // and apply conditions to filter unclaimed codes by type
      $user->load(['createdInviteCodes' => function ($query) {
        $query->where('claimed', 0);
      }]);

      // Separate collections based on user_role_id
      $audienceInviteCodes = $user->createdInviteCodes->where('user_role_id', 1)->values()->all();
      $vipInviteCodes = $user->createdInviteCodes->where('user_role_id', 3)->values()->all();
      $creatorInviteCodes = $user->createdInviteCodes->where('user_role_id', 4)->values()->all();

      return Inertia::render('Invite', [
          'inviteCodes' => [
              'audienceInviteCodes' => $audienceInviteCodes,
              'vipInviteCodes'      => $vipInviteCodes,
              'creatorInviteCodes'  => $creatorInviteCodes,
          ]
      ]);
    }
    // User is logged out

    // add logic here to display the invite code... enter invite code page.
    return Inertia::render('InviteCodes/LoggedOutInvitePage');
  }

  public function sendCreatorEmailInvitation(HttpRequest $request, InviteCode $inviteCode): \Illuminate\Http\RedirectResponse {
    $validatedRequest = $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email',
        'message' => 'required|string|max:3000',
    ]);

    $url = config('app.url') . '/invite/' . $inviteCode->ulid;
    $name = e($validatedRequest['name']);
    $email = $validatedRequest['email'];
    $message = e($validatedRequest['message']);
    $from_name = $inviteCode->createdBy->name;
    $invite_url = $url;
    $invite_code = $inviteCode->code;

    // Check if the email belongs to an already registered creator
    $creator = Creator::with('user')->whereHas('user', function ($query) use ($email) {
      $query->where('email', $email);
    })->first();

    // Send the email
    try {
      if ($creator) {
        // Send modified email to existing creator
        Mail::to($email)->send(new InviteExistingCreatorMail($name, $email, $message, $from_name));
        $request->session()->flash('success', "$name already has a creator account! We'll send them a reminder with your message.");
      } else {
        // Send the regular invitation email
        Mail::to($email)->send(new InviteCreatorMail($name, $email, $message, $from_name, $invite_url, $invite_code));
        $request->session()->flash('success', 'Invitation email sent successfully.');
      }

      return redirect()->route('invite')->with('success', $request->session()->get('success'));
    } catch (\Exception $e) {
      Log::error('Mail sending error from Creator Invitation Email form: ' . $e->getMessage());
      $request->session()->flash('error', 'There was an error submitting the form.');

      return redirect()->route('invite')->with('error', 'There was an error submitting the form.');
    }


    // if error return with('error', error);
    return redirect()->back()->with('success', 'Creator Email Invitation Form Submitted Successfully');
  }

  public function showCreatorInviteIntroduction(InviteCode $inviteCode): Response|\Illuminate\Http\RedirectResponse {
    // Verify the invite code...
//    $inviteCode = InviteCode::where('ulid', $ulid)->first();

//    if (!$inviteCode) {
//      // Handle invalid invite code: redirect or show error message
//      return redirect()->route('home')->with(['error' => 'Oops! Looks like you\'ve entered an invite code that doesn\'t match our records. Could you double-check that? We\'re here to help if you need it!']);
//
//    }

    if ($inviteCode->user_role_id !== 4) {
      // Handle case where invite code is not for a creator
      return redirect()->route('home')->with(['error' => 'Hmm, it seems like this invite code isn\'t meant for creators. If you\'re curious about different roles or have any questions, feel free to reach out to us!']);

    }

    if ($inviteCode->claimed) {
      // Handle case where invite code has already been claimed
      return redirect()->route('home')->with(['error' => 'It looks like this invite code has already found a home. But don\'t worry, more opportunities are just around the corner! Stay tuned, or get in touch if you think this is a mistake.']);
    }

    if ($inviteCode->expiry_date && Carbon::parse($inviteCode->expiry_date)->isPast()) {
      // Handle case where invite code has expired
      return redirect()->route('home')->with(['error' => 'Oops! It seems like this invite code has expired. But don\'t worry, opportunities are always on the horizon. Feel free to reach out to us if you have any questions or need further assistance!']);

    }

    // Proceed to show the creator invite introduction page
    return Inertia::render('Creators/Invite/Index', [
        'inviteCodeUlid' => $inviteCode->ulid
    ]);

  }

  /**
   * @throws ValidationException
   */
  public function checkInviteCode(HttpRequest $request, InviteCode $inviteCode): \Illuminate\Http\RedirectResponse {
    $validatedData = Validator::validate($request->all(), [
        'inviteCodeInput' => 'required|string',
    ]);
    if (strcasecmp($validatedData['inviteCodeInput'], $inviteCode->code) === 0) {
//          return redirect(route('creator.register.show', [$inviteCode->ulid]));
      return back()->with(['message' => 'Invite code is valid'], 200);
    } else {
      // Return an error response
      throw ValidationException::withMessages([
          'inviteCodeInput' => ['The provided invite code is incorrect.'],
      ]);
    }
  }

  /**
   * Check the registration access based on the provided invite code.
   *
   * @param Request $request
   * @param InviteCode $inviteCode The InviteCode model instance is automatically resolved by Laravel's route model binding.
   * @return JsonResponse
   */
  public function checkRegistrationAccess(HttpRequest $request, InviteCode $inviteCode): JsonResponse {
    if (!$request->inviteCode) {
      return response()->json(['success' => false, 'message' => 'No invite code provided.'], 500);
    }
    try {
      // Validate the incoming request
      $validated = $request->validate([
          'inviteCode' => 'required|string',
      ]);

      // Compare the provided invite code with the model's code
      if ($validated['inviteCode'] === $inviteCode->code) {
        // Return a JSON response indicating success
        $user = Auth::user();
        // Store the necessary data in the session
        Session::put('creatorRegistration', true);
        Session::put('inviteCode', $inviteCode->code);

        return response()->json(['success' => true], 200);
      } else {
        // Log the mismatch for auditing purposes
        Log::error("Invite code mismatch.", [
            'provided_invite_code' => $validated['inviteCode'],
            'expected_invite_code' => $inviteCode->code,
        ]);

        // Return a JSON response indicating failure
        return response()->json(['success' => false, 'message' => 'Invalid invite code.'], 400);
      }
    } catch (ModelNotFoundException $e) {
      // Log the exception
      Log::error("Invite code not found.", [
          'error' => $e->getMessage(),
      ]);

      // Return a JSON response for not found invite code
      return response()->json(['success' => false, 'message' => 'Invite code not found.'], 404);
    } catch (\Exception $e) {
      // Log any unexpected exceptions
      Log::error("An unexpected error occurred during the invite code check.", [
          'error' => $e->getMessage(),
      ]);

      // Return a JSON response for internal server error
      return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'], 500);
    }
  }

  public function showRegistrationForm(InviteCode $inviteCode): Response|\Illuminate\Http\RedirectResponse {
    $validationResult = $this->inviteCodeService->validateCode($inviteCode);

    if (!$validationResult['success']) {
      return redirect()->route('home')->with('error', $validationResult['message']);
    }

    $user = Auth::user();

    $isCreator = '';
    $isUser = '';

    if ($user && $user->isCreator()) {
      $isCreator = 'You’re already a part of the notTV family. Please log out to register a new creator.';
    } else if ($user && !$user->isCreator()) {
      $isUser = 'You’re almost there! Upgrade now to unlock your full potential as a notTV Creator.';
    }

    // Store the necessary data in the session
    Session::put('inviteCodeUlid', $inviteCode->ulid);

    // Code is valid, proceed to show the registration form
    return Inertia::render('Creators/Register/Index', [
        'inviteCodeUlid' => $inviteCode->ulid,
        'isCreator'      => $isCreator ?? null,
        'isUser'         => $isUser ?? null,
        'user'           => $user ? [
            'name'  => $user->name ?? null,
            'email' => $user->email ?? null,
            'country' => $user->country ?? null,
            'postalCode' => $user->postalCode ?? null,
        ] : null,
    ]);
  }

  public function register(HttpRequest $request, InviteCode $inviteCode) {
    // Check if the invite code has already been claimed
    if ($inviteCode->claimed) {
      return back()->withErrors(['invite_code' => 'The provided invite code has already been used.'])->withInput();
    }

    // Check if the user is authenticated
    $user = Auth::user();

    // Adjust validation rules
    $validationRules = [
        'name'                  => ['required', 'string', 'max:255'],
        'email'                 => ['required', 'string', 'email', 'max:255'],
        'address1'              => ['sometimes', 'string', 'max:255'],
        'address2'              => ['sometimes', 'string', 'max:255'],
        'city'                  => ['sometimes', 'string', 'max:255'],
        'province'              => ['sometimes', 'string', 'max:255'],
        'country'               => ['required', 'exists:news_countries,name'],
        'phone'                 => ['sometimes', 'string', 'max:255'],
        'postalCode'            => ['nullable', 'string', 'max:255'],
        'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
    ];

    // If the user is not authenticated, add password validation rules
    if (!$user) {
      $validationRules['password'] = array_merge(['required', 'confirmed'], $this->passwordRules());
      $validationRules['password_confirmation'] = ['required', 'same:password'];
    }

    // If the email does not match the authenticated user's email, ensure the email is unique
    if (!$user || ($user->email !== $request->email)) {
      $validationRules['email'][] = 'unique:users';
    }

    // Validate the request data including the invite code
    $validatedData = Validator::make($request->all(), $validationRules)->validate();

//    if ($user && $user->email !== $request->email) {
//      return back()->withErrors(['mismatched_email' => $user->name . ' is already logged in and this email address does not match. Please log out and try again.'])->withInput();
//    }


    // If the user is authenticated and the email matches but the user is not a creator
    if ($user && $user->email === $request->email && !$user->isCreator()) {
      // Proceed with updating user information or additional logic
      // For example, you could update their role to creator here
      return $this->updateUserToCreator($user, $validatedData, $inviteCode);
    }

    // Proceed with user creation if no errors
    try {
      $creatorAction = new CreateNewCreator();
      $user = $creatorAction->create($validatedData + ['invite_code' => $inviteCode]);
      $team = null;
      // If the invite code includes a team ID, add the user to the team
      if ($inviteCode->team_id) {
        // Fetch the team
        $team = Team::findOrFail($inviteCode->team_id);
        // Attach the user to the team's members
        $team->members()->attach($user->id);

        // Notify the new team member
        NotificationService::createAndDispatchNotification(
            $user->id,
            $team->image_id,
            '/teams/' . $team->slug,
            $team->name,
            '<span class="text-green-500">You have been added to the team.</span>'
        );
      }

      event(new Registered($user));
      event(new CreatorRegistrationCompleted($user, $inviteCode));

      Log::debug('Registered User and Creator Registration Completed.');
      // Redirect to check email for verification link page.
      return redirect('/check-email');
    } catch (\Exception $e) {
      Log::error('Registration failed: ' . $e->getMessage());

      return back()->withErrors(['registration' => 'An unexpected error occurred during registration. Please try again.'])->withInput();
    }
  }

  protected function updateUserToCreator($user, $validatedData, $inviteCode): \Illuminate\Http\RedirectResponse {
    // Begin a transaction to ensure data integrity
    DB::beginTransaction();

    try {
      // Update user information and set them as a creator
      $user->update([
          'name'       => $validatedData['name'],
          'address1'   => $validatedData['address1'] ?? null,
          'address2'   => $validatedData['address2'] ?? null,
          'city'       => $validatedData['city'] ?? null,
          'province'   => $validatedData['province'] ?? null,
          'country'    => $validatedData['country'],
          'postalCode' => $validatedData['postalCode'] ?? null,
          'phone'      => $validatedData['phone'] ?? null,
      ]);

      // Create a creator profile for the user
      $user->creator()->create([
          'invite_code_id' => $inviteCode->id,
      ]);

      // Increment the used count
      $inviteCode->used_count += 1;
      // Check if the invite code should be claimed
      if ($inviteCode->volume <= $inviteCode->used_count) {
        $inviteCode->claimed_by = $user->id;
        $inviteCode->claimed = true;
        $inviteCode->claimed_at = now();
      }
      $inviteCode->save();

      $team = null;

      // If the invite code includes a team ID, add the user to the team
      if ($inviteCode->team_id) {
        // Fetch the team
        $team = Team::findOrFail($inviteCode->team_id);
        // Attach the user to the team's members
        $team->members()->attach($user->id);

        // Notify the new team member
        NotificationService::createAndDispatchNotification(
            $user->id,
            $team->image_id,
            '/teams/' . $team->slug,
            $team->name,
            '<span class="text-green-500">You have been added to the team.</span>'
        );
      }

      // Dispatch an event indicating the creator registration has been completed
      event(new CreatorRegistrationCompleted($user, $inviteCode));

      // Log the completion of the registration process
      Log::debug('Creator Registration Completed with already registered user.');

      // Commit the transaction
      DB::commit();

      // Redirect to the dashboard with a full page reload
      return redirect('/dashboard');
    } catch (\Exception $e) {
      // Rollback the transaction in case of any error
      DB::rollBack();

      // Log the error
      Log::error('Error during creator registration: ' . $e->getMessage(), ['exception' => $e]);

      // Optionally, you can redirect back with an error message or handle it as per your application's needs
      return redirect()->back()->with('error', 'An error occurred during registration. Please try again.');
    }
  }

  public function markAsSeen(): \Illuminate\Http\JsonResponse {
    $user = Auth::user();
    $creator = Creator::where('user_id', $user->id)->first();

    if ($creator) {
      $creator->first_time = false;
      $creator->save();
    }

    return response()->json(['message' => 'Creator marked as seen.']);
  }

  public function registerCheckEmail(HttpRequest $request) {

    $email = $request->input('email');

    $exists = User::where('email', $email)->exists();

    return response()->json(['exists' => $exists]);
  }
//
//    // If the user is not authenticated and the email matches an existing user
//    $existingUser = User::where('email', $request->email)->first();
//    if ($existingUser) {
//      return back()->withErrors(['existing_email' => 'This email is already registered. Please log in to continue.'])->withInput();
//    }
//
//    if ($user && !$user->creator && $user->email === $request->email) {
//
//    }

//
//    // Proceed with user creation if no errors
//    try {
//      $creatorAction = new CreateNewCreator();
//      $user = $creatorAction->create($validatedData + ['invite_code' => $inviteCode]);
//
//      event(new Registered($user));
//      event(new CreatorRegistrationCompleted($user));
//
//      return redirect()->route('dashboard')->with('feedback', 'Welcome to the platform!');
//    } catch (\Exception $e) {
//      Log::error('Registration failed: ' . $e->getMessage());
//
//      return back()->withErrors(['registration' => 'An unexpected error occurred during registration. Please try again.'])->withInput();
//    }
//
//
//    // Validate the request data and the code.
//    // Create the user and associated creator record.
//    // Mark the invite code as used.
//    // Send a welcome email.
//    $inputData = $request->all() + ['invite_code' => $inviteCode]; // Adding invite code ID to the data array
//    $creatorAction = new CreateNewCreator();
//    try {
//      // Attempt to create the new creator, this method should return the User model on success
//      // or throw an Exception on failure
//      $user = $creatorAction->create($inputData);
//
//      // Assuming the creation was successful, redirect to the dashboard with a success message
//      return redirect()->route('dashboard')->with('feedback', 'Welcome to the platform!');
//      // TODO: Build a Welcome/Introduction page with progress bar (showOnBoardingStep($step))
//
//    } catch (\Exception $e) {
//      // Log the error message for debugging
//      Log::error('Registration failed: ' . $e->getMessage());
//
//      // Redirect back to the registration form with an error message.
//      // Assuming you're using Inertia.js, you might want to adjust this to fit its response style.
//      // If you're using traditional blade templates, you can use `redirect()->back()`
//      return back()->with('error', 'An unexpected error occurred during registration. Please try again.')->withInput();
//
//    }
//  }

  public function showOnboardingStep($step): Response {
    // Determine the view based on $step, or verify $step's validity...

    return Inertia::render('Creators/Onboarding/Index', compact('step'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(): Response {
    return Inertia::render('Creators/Index', [
        'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
            ->select('creators.*', 'user.name AS name')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10, ['*'], 'creators')
            ->withQueryString()
            ->through(fn($creator) => [
                'id'   => $creator->id,
                'name' => $creator->name
            ]),
        'filters'  => Request::only(['search'])
    ]);
  }

  /**
   * Display API listing of the resource.
   *
   * @param HttpRequest $request
   * @return AnonymousResourceCollection
   */
  public function getCreators(HttpRequest $request): AnonymousResourceCollection {
    $search = $request->input('search');

    $creatorsQuery = Creator::with('user', 'teams')
        ->when($search, function ($query) use ($search) {
          $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
          });
        })
        ->latest();

    $creators = $creatorsQuery->paginate(10);

    return CreatorResource::collection($creators)->additional([
        'filters' => $request->only(['search']),
    ]);
  }

  /**
   * Display API listing of the resource.
   *
   * @param HttpRequest $request
   * @return AnonymousResourceCollection
   */
  public function getAllCreators(HttpRequest $request): AnonymousResourceCollection {
    $search = $request->input('search');

    $creatorsQuery = Creator::with(['user.teams', 'user.teamManager'])
        ->when($search, function ($query) use ($search) {
          $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
          });
        })
        ->latest();

    $creators = $creatorsQuery->get();

    return CreatorResource::collection($creators);
  }


  public function fetchCreatorSettings(User $user) {
    // Ensure the authenticated user matches the requested user
    if (Auth::id() !== $user->id) {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Check if the user is a creator
    if (!$user->creator) {
      return response()->json(['error' => 'User is not a creator'], 400);
    }

    // Retrieve and return the creator settings
    return response()->json($user->creator->settings);
  }

  public function updateCreatorSettings(HttpRequest $request, User $user) {
    // Ensure the authenticated user matches the requested user
    if (Auth::id() !== $user->id) {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Validate the incoming request data for nested JSON
    $validatedData = $request->validate([
        'settings.profile_is_public' => 'required|boolean', // Access nested data
    ]);


    // After validation, you can access it like this:
    $profileIsPublic = $validatedData['settings']['profile_is_public'];

    // Check if the user is a creator and has settings to update
    if (!$user->creator || !$user->creator->settings) {
      return response()->json(['error' => 'Settings not found'], 404);
    }

    // Update the creator settings
    try {
      // Merge the existing settings with the validated data
      $currentSettings = $user->creator->settings ?: [];
      $currentSettings['profile_is_public'] = $profileIsPublic;
      $user->creator->settings = $currentSettings;
      $user->creator->save();

      // Return a success response
//      return response()->json([
//          'success'           => true,
//          'message'           => 'First play settings updated successfully.',
//          'firstPlaySettings' => $appSetting->first_play_settings,
//      ]);
      Log::info('Settings updated successfully 111.', $currentSettings);

      return redirect()->back()->with(['message' => 'Settings updated successfully!'], 200);

    } catch (\Exception $e) {
      // Serialize the error details into a string or simply pass a basic error message
      $detailedError = 'Failed to update settings: ' . $e->getMessage();

      // Redirect back with an error message
      return redirect()->back()->with('error', $detailedError);
    }
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(): Response {
    return Inertia::render('Creators/Create');
  }


  /**
   * Display the specified resource.
   *
   * @param Creator $creator
   * @return Response
   */
  public function show(Creator $creator): Response
  {
    $user = Auth::user();
    $component = $user ? 'Creators/{$id}/Index' : 'LoggedOut/Creators/{$id}/Index';

    $settings = $creator->settings;

    if (!isset($settings['profile_is_public']) || !$settings['profile_is_public']) {
      abort(404, 'Creator profile is not public.');
    }

    $creator->load(['user']);

    return Inertia::render($component, [
        'creator' => (new CreatorResource($creator))->resolve() ?? null,
    ]);
  }

  public function fetchTeams(Creator $creator): JsonResponse {
    $teams = Cache::remember("creator_{$creator->id}_teams", 60 * 10, function () use ($creator) {
      $creator->load(['teams.image.appSetting', 'teams.shows.image.appSetting']);
      return TeamResourceWithShows::collection($creator->teams)->resolve();
    });

    return response()->json($teams);
  }

  public function fetchNewsStories(Creator $creator): JsonResponse {
    $newsStories = Cache::remember("creator_{$creator->id}_news_stories", 60 * 10, function () use ($creator) {
      if ($creator->user && $creator->user->newsPerson) {
        return NewsStoryResource::collection($creator->user->newsPerson->newsStories()->published()->with('image.appSetting')->get())->resolve();
      }
      return [];
    });

    return response()->json($newsStories);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param Creator $creator
   * @return void
   */
  public function edit(Creator $creator) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param HttpRequest $request
   * @param Creator $creator
   * @return void
   */
  public function update(HttpRequest $request, Creator $creator) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Creator $creator
   * @return void
   */
  public function destroy(Creator $creator) {
    //
  }


}
