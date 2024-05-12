<?php

namespace App\Http\Controllers;


use App\Actions\Fortify\PasswordValidationRules;
use App\Events\CreatorRegistrationCompleted;
use App\Models\Creator;
use App\Models\InviteCode;
use App\Models\NewsCountry;
use App\Mail\InviteCreatorMail;
use App\Actions\Fortify\CreateNewCreator;
use App\Models\User;
use App\Rules\UnclaimedInviteCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
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
    $name = $validatedRequest['name'];
    $email = $validatedRequest['email'];
    $message = $validatedRequest['message'];
    $from_name = $inviteCode->createdBy->name;
    $invite_url = $url;
    $invite_code = $inviteCode->code;

    // Send the email
    try {
      Mail::to($validatedRequest['email'])->send(new InviteCreatorMail($name, $email, $message, $from_name, $invite_url, $invite_code));
//      Mail::to($validatedRequest['email'])->send(new InviteCreatorMail([
//          'message' => $validatedRequest['message'] ?? '',
//          'name'    => $validatedRequest['name'] ?? '',
//          'email'   => $validatedRequest['email'] ?? '',
//          'from_name' => auth()->user()->name,
//          'invite_url' => $url,
//          'invite_code' => $inviteCode->code,
//      ]));
    } catch (\Exception $e) {
      Log::error('Mail sending error from Creator Invitation Email form: ' . $e->getMessage());
      $request->session()->flash('error', 'There was an error submitting the form.');

      return redirect()->route('invite')->with('error', 'There was an error submitting the form.');
      // Optionally return an error response
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

    // Code is valid, proceed to show the registration form
    return Inertia::render('Creators/Register/Index', [
        'inviteCodeUlid' => $inviteCode->ulid,
        'user'           => $user ? [
            'name'  => $user->name,
            'email' => $user->email,
        ] : null,
    ]);
  }

  public function register(HttpRequest $request, InviteCode $inviteCode) {

    if ($inviteCode->claimed) {
      return back()->withErrors(['invite_code' => 'The provided invite code has already been used.'])->withInput();
    }

    // Check if the user is authenticated
    $user = Auth::user();

    if ($user && $user->email !== $request->email) {
      return back()->withErrors(['mismatched_email' => 'You are already logged in and this email address does not match. Please log out and try again or enter the correct email address.'])->withInput();
    }

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
        'password'              => array_merge(['required', 'confirmed'], $this->passwordRules()),
        'password_confirmation' => ['required', 'same:password'],
        'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        'postalCode'            => ['nullable', 'string', 'max:255'],
    ];

    if (!$user || ($user->email !== $request->email)) {
      $validationRules['email'][] = 'unique:users';
    }

    // Validate the request data including the invite code
    $validatedData = Validator::make($request->all(), $validationRules)->validate();


    $creatorAction = new CreateNewCreator();
    try {
      $user = $creatorAction->create($validatedData + ['invite_code' => $inviteCode]);

      event(new Registered($user));
      event(new CreatorRegistrationCompleted($user));

      return redirect()->route('dashboard')->with('feedback', 'Welcome to the platform!');
    } catch (\Exception $e) {
      Log::error('Registration failed: ' . $e->getMessage());

      return back()->withErrors(['registration' => 'An unexpected error occurred during registration. Please try again.'])->withInput();
    }


    // Validate the request data and the code.
    // Create the user and associated creator record.
    // Mark the invite code as used.
    // Send a welcome email.
    $inputData = $request->all() + ['invite_code' => $inviteCode]; // Adding invite code ID to the data array
    $creatorAction = new CreateNewCreator();
    try {
      // Attempt to create the new creator, this method should return the User model on success
      // or throw an Exception on failure
      $user = $creatorAction->create($inputData);

      // Assuming the creation was successful, redirect to the dashboard with a success message
      return redirect()->route('dashboard')->with('feedback', 'Welcome to the platform!');
      // TODO: Build a Welcome/Introduction page with progress bar (showOnBoardingStep($step))

    } catch (\Exception $e) {
      // Log the error message for debugging
      Log::error('Registration failed: ' . $e->getMessage());

      // Redirect back to the registration form with an error message.
      // Assuming you're using Inertia.js, you might want to adjust this to fit its response style.
      // If you're using traditional blade templates, you can use `redirect()->back()`
      return back()->with('error', 'An unexpected error occurred during registration. Please try again.')->withInput();

    }
  }

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
   * @return Builder[]|Collection
   */
  public function getCreators(HttpRequest $request): Collection|array {
    $creator = Creator::with('user')->get();

    return $creator;

    $data = Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
        ->select('creators.*', 'user.name AS name')
        ->when(Request::input('search'), function ($query, $search) {
          $query->where('name', 'LIKE', "%{$search}%")->get();
        });

    return ([
        'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
            ->select('creators.*', 'user.name AS name')
            ->with('user')
            ->when(Request::input('search'), function ($query, $search) {
              $query->where('name', 'LIKE', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString()
            ->through(fn($creator) => [
                'id'   => $creator->user_id,
                'name' => $creator->user->name
            ]),
        'filters'  => Request::only(['search'])
    ])->toJSON();

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
  public function show(Creator $creator): Response {

    return Inertia::render('Creators/{$id}/Index', [
        'creator' => $creator,
    ]);
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
