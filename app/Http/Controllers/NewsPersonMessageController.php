<?php

namespace App\Http\Controllers;

use App\Events\NewNewsPersonMessage;
use App\Http\Resources\NewsPersonMessageResource;
use App\Models\NewsPersonMessage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class NewsPersonMessageController extends Controller {

  public function __construct() {
//    $this->authorizeResource(NewsPersonMessage::class, 'newsPersonMessage');
//    $this->middleware('can:fetch,App\Models\NewsPersonMessage')->only(['fetchMessages']);
  }

  /**
   * Display a listing of the resource.
   * @throws AuthorizationException
   */
  public function index(): \Inertia\Response {

    $this->authorize('viewAny', NewsPersonMessage::class);

    $user = auth()->user();
    $messages = NewsPersonMessage::where('recipient_id', $user->newsPerson->id)->latest()->get();

    return Inertia::render('NewsPersonMessages/Index', [
        'messages' => NewsPersonMessageResource::collection($messages)->resolve(),
    ]);
  }

  /**
   * @throws AuthorizationException
   */
  public function fetchMessages(Request $request): \Illuminate\Http\JsonResponse {

    $this->authorize('fetch', NewsPersonMessage::class);

    $user = $request->user();
    $messages = NewsPersonMessage::where('recipient_id', $user->newsPerson->id)->latest()->get();

    return response()->json(NewsPersonMessageResource::collection($messages)->resolve());
  }

  /**
   * @throws AuthorizationException
   */
  public function count(Request $request): \Illuminate\Http\JsonResponse {

    $this->authorize('viewAny', NewsPersonMessage::class);

    $user = $request->user();
    $count = NewsPersonMessage::where('recipient_id', $user->newsPerson->id)->count();

    return response()->json(['count' => $count]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): \Inertia\Response {
    return Inertia::render('NewsPersonMessages/Create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse {

    // Validate the request data
    $validatedData = $request->validate([
        'recipient_id' => 'required|exists:news_people,id',
        'subject'      => 'nullable|string',
        'message'      => 'required|string',
    ]);

    // Set the sender_id to the authenticated user id or null if not authenticated
    $validatedData['sender_id'] = Auth::id();

    // Sanitize the subject and message
    $validatedData['subject'] = e($validatedData['subject']);
    $validatedData['message'] = e($validatedData['message']);

    // Encrypt the subject and message
    $validatedData['subject'] = $validatedData['subject'] ? Crypt::encryptString($validatedData['subject']) : null;
    $validatedData['message'] = Crypt::encryptString($validatedData['message']);

    // Create the message
    NewsPersonMessage::create($validatedData);

    // Broadcast the event
    broadcast(new NewNewsPersonMessage($validatedData['recipient_id']))->toOthers();


    // Redirect to the messages index
//    return redirect()->route('news-person-messages.index');
    return redirect('/news-person-messages/')->with('message', 'Message sent successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(NewsPersonMessage $newsPersonMessage): \Inertia\Response
  {
    $this->authorize('view', $newsPersonMessage);

    if (is_null($newsPersonMessage->read_at)) {
      $newsPersonMessage->update(['read_at' => now()]);
    }

    return Inertia::render('NewsPersonMessages/{$id}', [
        'newsPersonMessage' => (new NewsPersonMessageResource($newsPersonMessage))->resolve(),
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(NewsPersonMessage $newsPersonMessage): \Inertia\Response {
    return Inertia::render('NewsPersonMessages/Edit', [
        'newsPersonMessage' => $newsPersonMessage,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, NewsPersonMessage $newsPersonMessage): \Illuminate\Http\RedirectResponse {
    $request->validate([
        'message' => 'required|string',
    ]);

    $newsPersonMessage->update($request->all());

    return redirect()->route('news-person-messages.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(NewsPersonMessage $newsPersonMessage): \Illuminate\Http\RedirectResponse {

    $this->authorize('delete', $newsPersonMessage);

    $newsPersonMessage->delete();

    return redirect()->route('news-person-messages.index')
        ->with('success', 'Message deleted successfully');
  }

  public function deleteAll(Request $request)
  {
    $user = Auth::user();

    $this->authorize('deleteAll', NewsPersonMessage::class);

    $newsPersonId = $user->newsPerson->id;
    NewsPersonMessage::where('recipient_id', $newsPersonId)->delete();

    return redirect()->route('news-person-messages.index')
        ->with('success', 'Message deleted successfully');
  }

  public function restore($id): \Illuminate\Http\RedirectResponse {
    $newsPersonMessage = NewsPersonMessage::withTrashed()->findOrFail($id);

    $this->authorize('restore', $newsPersonMessage);

    $newsPersonMessage->restore();

    return redirect()->route('news-person-messages.index')
        ->with('success', 'Message restored successfully');
  }

  public function forceDelete($id): \Illuminate\Http\RedirectResponse {
    $newsPersonMessage = NewsPersonMessage::withTrashed()->findOrFail($id);

    $this->authorize('forceDelete', $newsPersonMessage);

    $newsPersonMessage->forceDelete();

    return redirect()->route('news-person-messages.index')
        ->with('success', 'Message permanently deleted');
  }
}
