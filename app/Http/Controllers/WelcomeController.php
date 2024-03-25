<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use App\Mail\InviteRequestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class WelcomeController extends Controller {
  public function index() {
    $session_id = session()->getId();

    if (Auth::check()) {
      // User is logged in, update last login time
      $user = Auth::user();
      $user->update(['last_login_at' => now()]);

      // Redirect the user to the stream route
      if ($user->creator) {
        return redirect()->route('dashboard');
      }
      return redirect()->route('stream');
    }

    return Inertia::render('Welcome', [
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
        'phone'   => 'nullable|regex:/^\+?[0-9]{1,3}?[-.\s]?(\([0-9]{1,3}?\))?[-.\s]?[0-9]{1,4}?[-.\s]?[0-9]{1,4}?[-.\s]?[0-9]{1,9}$/',
        'message' => 'required|string|max:3000',
    ]);

    $phoneNumber = $request->input('phone');
    $sanitizedPhoneNumber = preg_replace('/[^\d+]+/', '', $phoneNumber);

    // Send the email
    try {
      Mail::to('hello@not.tv')->send(new ContactUsMail([
          'message' => $validatedRequest['message'] ?? '',
          'name'    => $validatedRequest['name'] ?? '',
          'email'   => $validatedRequest['email'] ?? '',
          'phone'   => $sanitizedPhoneNumber ?? '',
      ]));
    } catch (\Exception $e) {
      Log::error('Mail sending error from Contact Us form: ' . $e->getMessage());
      $request->session()->flash('error', 'There was an error submitting the form.');
      // Optionally return an error response
    }


    // if error return with('error', error);
    return redirect()->route('public.contact')->with('success', 'Contact Form Submitted Successfully');

  }

  public function inviteFormSubmission(HttpRequest $request) {


    if ($request->confirm_email || $request->fax || $request->website) {
      // Log this attempt, if necessary
      Log::info('Honeypot fields filled', $request->all());

      // Return a generic success response to not alert the bot
      return response()->json(['message' => 'Your submission has been received, thank you!'], 200);
    }

    $validatedRequest = $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email',
        'phone'   => 'nullable|regex:/^\+?[0-9]{1,3}?[-.\s]?(\([0-9]{1,3}?\))?[-.\s]?[0-9]{1,4}?[-.\s]?[0-9]{1,4}?[-.\s]?[0-9]{1,9}$/',
        'message' => 'required|string|max:3000',
    ]);

    $phoneNumber = $request->input('phone');
    $sanitizedPhoneNumber = preg_replace('/[^\d+]+/', '', $phoneNumber);

    // Send the email
    try {
      Mail::to('hello@not.tv')->send(new InviteRequestMail([
          'message' => $validatedRequest['message'] ?? '',
          'name'    => $validatedRequest['name'] ?? '',
          'email'   => $validatedRequest['email'] ?? '',
          'phone'   => $sanitizedPhoneNumber ?? '',
      ]));
    } catch (\Exception $e) {
      Log::error('Mail sending error from Invite Request form: ' . $e->getMessage());
      $request->session()->flash('error', 'There was an error submitting the form.');
      // Optionally return an error response
    }


    // if error return with('error', error);
    return redirect()->route('invite')->with('success', 'Invite Request Form Submitted Successfully');

  }
}
