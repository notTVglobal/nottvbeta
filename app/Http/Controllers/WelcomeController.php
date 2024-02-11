<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class WelcomeController extends Controller {
  public function index() {
    $session_id = session()->getId();

    if (auth()->user()) {
      return to_route('stream');
    }

    return Inertia::render('Welcome', [
//            'canLogin' => Route::has('login'),
//            'canRegister' => Route::has('register'),
        'user_id'       => $session_id,
        'logged_out_id' => $session_id,
    ]);
  }

  public function contactFormSubmission(HttpRequest $request) {

    if ($request->confirm_email || $request->fax || $request->website) {
      // Log this attempt, if necessary
       Log::info('Honeypot fields filled', $request->all());

      // Return a generic success response to not alert the bot
      return response()->json(['message' => 'Your submission has been received, thank you!'], 200);
    }

    $validatedRequest = $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email',
        'phone'   => 'nullable|regex:/^\+?[1-9]\d{1,14}$/',
        'message' => 'required|string|max:3000',
    ]);

    // Send the email
    try {
      Mail::to('hello@not.tv')->send(new ContactUsMail([
          'message' => $validatedRequest['message'] ?? '',
          'name' => $validatedRequest['name'] ?? '',
          'email' => $validatedRequest['email'] ?? '',
          'phone' => $validatedRequest['phone'] ?? '',
      ]));
    } catch (\Exception $e) {
      Log::error('Mail sending error from Contact Us form: ' . $e->getMessage());
      $request->session()->flash('error', 'There was an error submitting the form.');
      // Optionally return an error response
    }



    // if error return with('error', error);
    return redirect()->route('public.contact')->with('success', 'Contact Form Submitted Successfully');

  }
}
