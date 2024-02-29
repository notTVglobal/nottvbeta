<?php

namespace App\Http\Controllers;

use App\Models\InviteCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;
use App\Models\Creator;
use Inertia\Response;
use App\Services\InviteCodeService;

class CreatorsController extends Controller
{

  protected $inviteCodeService;

  public function __construct(InviteCodeService $inviteCodeService)
  {
    $this->inviteCodeService = $inviteCodeService;
  }

  public function showCreatorInviteIntroduction($code)
  {
    // Verify the invite code...
    $inviteCode = InviteCode::where('code', $code)->first();

    if (!$inviteCode) {
      // Handle invalid invite code: redirect or show error message
      return redirect()->route('home')->with(['error' => 'Oops! Looks like you\'ve entered an invite code that doesn\'t match our records. Could you double-check that? We\'re here to help if you need it!']);

    }

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
      return redirect()->route('home')->with(['error' => 'It looks like this invite code has already found a home. No worries, our doors are always open for new opportunities. Stay tuned, or let us know if you’d like a hand!']);

    }

    // Proceed to show the creator invite introduction page
    return Inertia::render('Creators/Invite/Index', [
        'inviteCode' => $code
    ]);

  }

  public function showRegistrationForm($code)
  {
    $validationResult = $this->inviteCodeService->validateCode($code);

    if (!$validationResult['success']) {
      return redirect()->route('home')->with('error', $validationResult['message']);
    }

    // Code is valid, proceed to show the registration form
    return Inertia::render('Creators/Register/Index', ['code' => $code]);
  }

  public function registerCreator(Request $request, $code)
  {
    // Validate the request data and the code.
    // Create the user and associated creator record.
    // Mark the invite code as used.
    // Send a welcome email.
    // Redirect to the Welcome/Introduction page with progress bar.
  }

  public function showOnboardingStep($step)
  {
    // Determine the view based on $step, or verify $step's validity...

    return Inertia::render('Creators/Onboarding/Index', compact('step'));
  }




    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
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
    public function getCreators(HttpRequest $request)
    {
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
    public function create()
    {
        return Inertia::render('Creators/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HttpRequest $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $creator = Creator::query()->where('user_id', $id)->firstOrFail();

        return Inertia::render('Creators/{$id}/Index', [
            'creator' => $creator,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }


}
