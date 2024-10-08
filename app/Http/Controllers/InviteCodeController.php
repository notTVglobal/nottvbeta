<?php

namespace App\Http\Controllers;

use App\Helpers\TimezoneConverter;
use App\Http\Resources\InviteCodeResource;
use App\Models\Creator;
use App\Models\InviteCode;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class InviteCodeController extends Controller {

  public function __construct() {
    // Apply authorization middleware to specific controller actions
//    $this->middleware('can:view,App\Models\InviteCode')->only(['viewMyCodes']);
  }

  /**
   * Display a listing of the invite codes.
   *
   * @return Response
   */
  public function index(Request $request): Response {

//    $inviteCodes = InviteCode::with('createdBy', 'role')->latest()->paginate(10); // Adjust the number as per your requirement
//
//    return Inertia::render('InviteCodes/Index', [
//        'inviteCodes' => InviteCodeResource::collection($inviteCodes)
//    ]);

    // Capture search input from the request
    $search = $request->input('search');

    // Start the query with relationships and default ordering
    $inviteCodesQuery = InviteCode::with('createdBy', 'role')->latest();

    // Apply search filter if provided
    if ($search) {
      $inviteCodesQuery->where(function ($query) use ($search) {
        $query->where('code', 'like', "%{$search}%")
            ->orWhereHas('createdBy', function ($query) use ($search) {
              $query->where('name', 'like', "%{$search}%");
            });
      });
    }

    // Paginate the results
    $inviteCodes = $inviteCodesQuery->paginate(10); // Adjust the pagination as needed

    // Return the Inertia response
    return Inertia::render('InviteCodes/Index', [
        'inviteCodes' => InviteCodeResource::collection($inviteCodes),
      // Include additional data as needed, for example:
        'filters'     => $request->only(['search']), // Pass current filters back to the view for UI consistency
      // You might want to include additional data for the frontend here
    ]);

//    return Inertia::render('InviteCodes/Index', [
//        'inviteCodes' => InviteCodeResource::collection($inviteCodes),
//        'filters' => Request::only(['search']),
//    ]);

  }

  public function inviteCodeQuickAdd(HttpRequest $request) {
//        $inviteCodes = $request->validate([
//            'cdn_endpoint' => 'nullable|string',
//            'cloud_folder' => 'nullable|string',
//        ]);

    $validatedData = $request->validate([
        'code' => ['required', 'unique:invite_codes', 'string', 'max:20'],
    ]);

    $invite_code = new InviteCode;
    $invite_code->created_by = Auth::user()->id;
    $invite_code->code = $request->code;
    $invite_code->user_role_id = 1;
    $invite_code->volume = 1;
    $invite_code->notes = 'quick add';
    $invite_code->save();

//        return $invite_code;
    // redirect
    return redirect()->route('inviteCodes')->with('success', 'Code Added Successfully');

  }

  public function exportInviteCodes() {

    // tec21: this needs to

    $codes = InviteCode::all()->toArray();


//        dd($codes);

    $handle = fopen('../storage/app/csv/invite_codes.csv', 'w');
//
//        collect($data)->each(fn ($row) => fputcsv($handle, $data));
//        fputcsv($handle, $data);

//
    foreach ($codes as $line) {
      fputcsv($handle, $line);
    }

    fclose($handle);

//return $codes;
    return redirect()->route('inviteCodes')->with('success', 'exported successfully.');
  }


  public function viewMyCodes(): Response {
    $user = Auth::user();

    // Calculate the date one week ago
    $oneWeekAgo = Carbon::now()->subWeek();

    // Fetch invite codes that belong to the authenticated user and meet the criteria
    $codes = InviteCode::with('createdBy', 'role', 'claimedBy')
        ->where('created_by', $user->id)
        ->where(function ($query) use ($oneWeekAgo) {
          $query->where('claimed', false) // Not claimed
          ->orWhere('claimed', true)
              ->where('claimed_at', '>=', $oneWeekAgo); // Claimed within the past week
        })
        ->latest()
        ->paginate(10); // Eager load the distributor

    // Check if the user has any invite codes
    if ($codes->isEmpty()) {
      // User has no codes, render a view or return a message
      return Inertia::render('InviteCodes/NoCodes', [
          'can' => [
              'viewDashboard' => Auth::user()->can('viewDashboard', Creator::class),
          ],
      ]);
    }

    return Inertia::render('InviteCodes/MyCodes', [
        'codes' => InviteCodeResource::collection($codes)
    ]);
  }


  /**
   * Display a report of the invite codes.
   *
   * @return Response
   */
  public function report(): Response {
    $codes = InviteCode::with('createdBy', 'role')->latest()->paginate(10); // Eager load the distributor

    return Inertia::render('InviteCodes/Report', [
        'codes' => InviteCodeResource::collection($codes)
    ]);
  }

  /**
   * Show the form for creating a new invite code.
   *
   * @return Response
   */
  public function create(): Response {
    $roles = Role::where('id', '!=', 2)->get(); // Exclude role with id 2
    $suggestedCode = $this->generateUniqueInviteCode(); // Generate a suggested code

    return Inertia::render('InviteCodes/Create', ['roles' => $roles, 'suggestedCode' => $suggestedCode]);
  }

  /**
   * Store a newly created invite code in the database.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws \Exception
   */
  public function store(Request $request): RedirectResponse {
    $request->validate([
        'code'               => [
            'required',
            'string',
            'unique:invite_codes,code',
            'regex:/^[A-Za-z0-9]+$/'
        ],
        'created_by_user_id' => 'nullable|exists:users,id',
        'user_role_id'       => 'nullable|exists:roles,id',
        'volume'             => 'required|integer|min:1',
        'expiry_date'        => 'nullable|date',
        'user_timezone'      => 'nullable|string',
    ], [
        'code.regex' => 'The :attribute must consist of only letters and numbers.',
    ]);

    $userId = auth()->id();

    if ($request->created_by_user_id) {
      $userId = $request->created_by_user_id;
    }

    $userTimezone = $request->user_timezone ?? 'UTC'; // e.g., 'America/New_York'
    $expiryDate = $request->expiry_date; // e.g., '2024-03-23'
    if ($expiryDate !== null) {
      $expiryDateUtc = TimezoneConverter::convertUserTimeToUtc($expiryDate, $userTimezone);
    } else {
      // If $expiryDate is null, you can directly set $expiryDateUtc to null
      // since there's no date to convert.
      $expiryDateUtc = null;
    }

    InviteCode::create([
        'code'         => $request->code,
        'user_role_id' => $request->user_role_id,
        'volume'       => $request->volume,
        'used_count'   => 0, // Starts unused
        'expiry_date'  => $expiryDateUtc,
        'created_by'   => $userId,
    ]);

    return redirect()->route('inviteCodes')->with('success', 'Invite code created successfully.');
  }

  // Add methods for edit, update, and delete as necessary

  public function edit(InviteCode $inviteCode): Response {
    // Eager load the createdBy and role relationships
    $inviteCode->load(['createdBy', 'role']);

    // Fetch roles excluding the one with id 2
    $roles = Role::where('id', '!=', 2)->get();

    // Resolve the InviteCodeResource to a plain array
    $inviteCodeArray = (new InviteCodeResource($inviteCode))->resolve();

    // Return the Inertia response with the inviteCode and roles
    return Inertia::render('InviteCodes/Edit', [
        'inviteCode' => $inviteCodeArray,
        'roles'      => $roles
    ]);
  }

  /**
   * @throws \Exception
   */
  public function update(Request $request, InviteCode $inviteCode): \Illuminate\Http\JsonResponse {
    $request->validate([
        'code'               => [
            'required',
            'string',
            'unique:invite_codes,code,' . $inviteCode->id,
            'regex:/^[A-Za-z0-9]+$/'
        ],
        'created_by_user_id' => 'nullable|exists:users,id',
        'user_role_id'       => 'nullable|exists:roles,id',
        'volume'             => 'required|integer|min:1',
        'expiry_date'        => 'nullable|date',
        'user_timezone'      => 'nullable|string',
    ], [
        'code.regex' => 'The :attribute must consist of only letters and numbers.',
    ]);

    $userId = auth()->id();

    if ($request->created_by_user_id) {
      $userId = $request->created_by_user_id;
    }

    // Prevent volume reduction below uses
    if ($request->volume < $inviteCode->uses) {
      // Return error message
      return response()->json([
          'errors' => ['volume' => 'Cannot reduce volume below current uses.']
      ], 422); // 422 Unprocessable Entity
    }

    $userTimezone = $request->user_timezone ?? 'UTC'; // e.g., 'America/New_York'
    $expiryDate = $request->expiry_date; // e.g., '2024-03-23'
    if ($expiryDate !== null) {
      $expiryDateUtc = TimezoneConverter::convertUserTimeToUtc($expiryDate, $userTimezone);
    } else {
      // If $expiryDate is null, you can directly set $expiryDateUtc to null
      // since there's no date to convert.
      $expiryDateUtc = null;
    }

//// Create a Carbon instance of the expiry date at 00:00:00 in the user's timezone
//    $expiryDateUserTimezone = Carbon::createFromFormat('Y-m-d H:i:s', "{$expiryDate} 00:00:00", $userTimezone);
//
//// Convert the date to UTC
//    $expiryDateUtc = $expiryDateUserTimezone->setTimezone('UTC');

    $inviteCode->update([
        'code'         => $request->code,
        'user_role_id' => $request->user_role_id,
        'volume'       => $request->volume,
        'expiry_date'  => $expiryDateUtc,
        'created_by' => $userId,
    ]);

    // Set claimed to true if uses reaches volume
    if ($inviteCode->uses >= $inviteCode->volume) {
      $inviteCode->claimed = true;
      $inviteCode->save();
    }

    return response()->json(['message' => 'Invite code updated successfully.']);
  }

  public function destroy(InviteCode $inviteCode): RedirectResponse {
    $inviteCode->delete();

    return redirect()->route('inviteCodes')->with('success', 'Invite code deleted successfully.');
  }

  public function claimCode(Request $request, $codeId): \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector {
    $code = InviteCode::find($codeId);
    if ($code && !$code->claimed) {
      $code->claimed = 1;
      $code->claimed_by = auth()->user()->id; // Set the claimed_by field
      $code->claimed_at = now(); // Set the claimed_at field to the current datetime
      $code->save();

      return redirect(route('inviteCodes'));
    }

    return redirect(route('inviteCodes'))->with('error', 'Code not found or already claimed.');
  }

  public function claimAllCodes(Request $request): \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector {
    $unclaimedCodes = InviteCode::where('claimed', 0)->get(); // Get all unclaimed codes

    foreach ($unclaimedCodes as $code) {
      $code->claimed = 1;
      $code->claimed_by = auth()->user()->id; // Set the claimed_by field
      $code->claimed_at = now(); // Set the claimed_at field to the current datetime
      $code->save();
    }

    return redirect(route('inviteCodes'))->with('message', 'All unclaimed codes have been successfully claimed.');
  }

  public function generateUniqueInviteCode(): string {
    $adjectives = [
        'Sunny', 'Happy', 'Joyful', 'Vibrant', 'Serene', 'Golden', 'Silent', 'Gleaming', 'Tranquil',
        'Azure', 'Emerald', 'Scarlet', 'Pastel', 'Twilight', 'Dewy', 'Flourishing', 'Luminous', 'Pristine',
        'Radiant', 'Sparkling', 'Majestic', 'Ethereal', 'Harmonious', 'Blossoming', 'Blissful', 'Illuminated',
        'Peaceful', 'Refreshing', 'Enchanting'
    ];


    $nouns = [
        'Mountain', 'River', 'Forest', 'Valley', 'Ocean', 'Lake', 'Tree', 'Cloud', 'Bird', 'Flower',
        'Meadow', 'Glacier', 'Canyon', 'Garden', 'Peak', 'Fjord', 'Orchard', 'Vineyard',
        'Rainbow', 'Butterfly', 'Breeze', 'Moonlight', 'Starlight', 'Sunset', 'Dawn', 'Horizon', 'Pond',
        'Sanctuary', 'Brook', 'Sunrise', 'Haven', 'Paradise', 'Bloom', 'Heaven'
    ];


    do {
      $code = $adjectives[array_rand($adjectives)] . $nouns[array_rand($nouns)];

      // Check uniqueness and append two random digits if necessary
      if (InviteCode::where('code', $code)->exists()) {
        $code .= rand(10, 99);
      }

      // Double-check the modified code's uniqueness
      $unique = !InviteCode::where('code', $code)->exists();
    } while (!$unique);

    return $code;
  }

  public function generateUniqueInviteCodeJson(): \Illuminate\Http\JsonResponse {
    $code = $this->generateUniqueInviteCode(); // Use the method you've defined to generate the code

    return response()->json(['suggestedCode' => $code]);
  }

}
