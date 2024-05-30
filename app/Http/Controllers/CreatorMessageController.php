<?php

namespace App\Http\Controllers;

use App\Models\CreatorMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreatorMessageController extends Controller {

  public function __construct() {
    $this->authorizeResource(CreatorMessage::class, 'creatorMessage');
  }

  /**
   * Display a listing of the resource.
   */
  public function index() {
    $user = auth()->user();
    $messages = CreatorMessage::where('sender_id', $user->id)->get();

    return Inertia::render('CreatorMessages/Index', [
        'messages' => $messages,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create() {
    return Inertia::render('CreatorMessages/Create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request) {
    $request->validate([
        'sender_id'    => 'required|exists:users,id',
        'recipient_id' => 'required|exists:creators,id',
        'message'      => 'required|string',
    ]);

    CreatorMessage::create($request->all());

    return redirect()->route('creator-messages.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(CreatorMessage $creatorMessage) {
    if (is_null($creatorMessage->read_at)) {
      $creatorMessage->update(['read_at' => now()]);
    }

    return Inertia::render('CreatorMessages/Show', [
        'creatorMessage' => $creatorMessage,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(CreatorMessage $creatorMessage) {
    return Inertia::render('CreatorMessages/Edit', [
        'creatorMessage' => $creatorMessage,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, CreatorMessage $creatorMessage) {
    $request->validate([
        'message' => 'required|string',
    ]);

    $creatorMessage->update($request->all());

    return redirect()->route('creator-messages.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(CreatorMessage $creatorMessage) {
    $creatorMessage->delete();

    return redirect()->route('creator-messages.index')
        ->with('success', 'Message deleted successfully');
  }

  public function restore($id) {
    $creatorMessage = CreatorMessage::withTrashed()->findOrFail($id);
    $creatorMessage->restore();

    return redirect()->route('creator-messages.index')
        ->with('success', 'Message restored successfully');
  }

  public function forceDelete($id) {
    $creatorMessage = CreatorMessage::withTrashed()->findOrFail($id);
    $creatorMessage->forceDelete();

    return redirect()->route('creator-messages.index')
        ->with('success', 'Message permanently deleted');
  }
}
