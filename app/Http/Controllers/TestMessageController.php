<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Events\NewChatTestMessage;
use Inertia\Inertia;


class TestMessageController extends Controller
{

    public function index()
    {
        return Inertia::render('ChatTest');
    }


    public function broadcast(HttpRequest $request) {

        if (! $request->filled('message')) {
            return response()->json([
                'message' => 'No message to send'
            ], 422);
        }

        // TODO: Sanitize input

        event(new NewChatTestMessage($request->message));
        return response()->json(['success'], 201);

    }
}
