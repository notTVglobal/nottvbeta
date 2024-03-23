<?php

namespace App\Http\Controllers;


use App\Models\Creator;
use App\Models\InviteCode;
use App\Models\NewsCountry;
use App\Mail\InviteCreatorMail;
use App\Actions\Fortify\CreateNewCreator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

class CreatorsController extends Controller
{

  protected InviteCodeService $inviteCodeService;

  public function __construct(InviteCodeService $inviteCodeService)
  {
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
              'vipInviteCodes' => $vipInviteCodes,
              'creatorInviteCodes' => $creatorInviteCodes,
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
        if (strcasecmp($validatedData['inviteCodeInput'], $inviteCode->code) === 0){
//          return redirect(route('creator.register.show', [$inviteCode->ulid]));
          return back()->with(['message' => 'Invite code is valid'], 200);
        } else {
          // Return an error response
          throw ValidationException::withMessages([
              'inviteCodeInput' => ['The provided invite code is incorrect.'],
          ]);
        }
  }

  public function showRegistrationForm(InviteCode $inviteCode): Response|\Illuminate\Http\RedirectResponse {
    $validationResult = $this->inviteCodeService->validateCode($inviteCode);

    if (!$validationResult['success']) {
      return redirect()->route('home')->with('error', $validationResult['message']);
    }

    // Code is valid, proceed to show the registration form
    return Inertia::render('Creators/Register/Index', [
        'inviteCodeUlid' => $inviteCode->ulid,
    ]);
  }

    public function register(Request $request, InviteCode $inviteCode): void {
    dd($request);
      // Validate the request data and the code.
      // Create the user and associated creator record.
      // Mark the invite code as used.
      $inputData = $request->all() + ['invite_code' => $inviteCode->code]; // Adding invite code ID to the data array
      $creatorAction = new CreateNewCreator();
      $creatorAction->create($inputData);

      // Send a welcome email.
      // Redirect to the Welcome/Introduction page with progress bar.

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
                    'id' => $creator->id,
                    'name' => $creator->name
                ]),
            'filters' => Request::only(['search'])
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
            ->when(Request::input('search'), function($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")->get();
            });

        return ([
            'creators' => Creator::join('users AS user', 'creators.user_id', '=', 'user.id')
        ->select('creators.*', 'user.name AS name')
        ->with('user')
        ->when(Request::input('search'), function($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($creator) => [
                    'id' => $creator->user_id,
                    'name' => $creator->user->name
                ]),
            'filters' => Request::only(['search'])
        ])->toJSON();

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
     * @param  Creator $creator
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
     * @param  Creator $creator
     * @return void
     */
    public function edit(Creator $creator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HttpRequest $request
     * @param Creator $creator
     * @return void
     */
    public function update(HttpRequest $request, Creator $creator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Creator $creator
     * @return void
     */
    public function destroy(Creator $creator)
    {
        //
    }


}
