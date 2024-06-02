<?php

namespace App\Http\Controllers;

use App\Events\NewNewsPersonMessage;
use App\Mail\NewsTipMailer;
use App\Models\NewsPerson;
use App\Models\NewsPersonMessage;
use App\Models\NewsTip;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class NewsTipController extends Controller {
  public function submitNewsTip(Request $request) {

    try {
    // Validate the request input
    $validatedData = $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|max:255',
        'phone'          => 'nullable|string|max:255',
        'postalCode'     => 'nullable|string|max:255',
        'message'        => 'required|string|max:5000',
        'news_person_id' => 'nullable|exists:news_people,id',
    ]);

    // Escape and Encrypt the input data
    $escapedName = e($validatedData['name']);
    $escapedEmail = e($validatedData['email']);
    $escapedPhone = e($validatedData['phone']) ? e($validatedData['phone']) : null;
    $escapedMessage = e($validatedData['message']);
    $escapedPostalCode = isset($validatedData['postalCode']) ? e($validatedData['postalCode']) : null;

    $encryptedName = Crypt::encryptString($escapedName);
    $encryptedEmail = Crypt::encryptString($escapedEmail);
    $encryptedPhone = $escapedPhone ? Crypt::encryptString($escapedPhone) : null;
    $encryptedMessage = Crypt::encryptString($escapedMessage);
    $encryptedPostalCode = $escapedPostalCode ? Crypt::encryptString($escapedPostalCode) : null;

    // Prepare the validated data for creating the news tip
    $validatedData['name'] = $encryptedName;
    $validatedData['email'] = $encryptedEmail;
    $validatedData['phone'] = $encryptedPhone;
    $validatedData['message'] = $encryptedMessage;
    $validatedData['postalCode'] = $encryptedPostalCode;


    // Create the news tip in the database
    $newsTip = NewsTip::create($validatedData);

    // Fetch all news_people with roles 8 or 11 if news_person_id is null
    if (isset($validatedData['news_person_id'])) {
      $newsPeople = [NewsPerson::find($validatedData['news_person_id'])];
    } else {
      $newsPeople = NewsPerson::whereHas('roles', function ($query) {
        $query->whereIn('news_people_roles.id', [8, 11]);
      })->get();
    }

    foreach ($newsPeople as $newsPerson) {
      $recipientEmail = $newsPerson->user->email ?? null;
      if ($recipientEmail) {
        $this->createAndBroadcastNewsPersonMessage(array_merge($validatedData, ['news_person_id' => $newsPerson->id]), $newsTip);
        // Send notification email
        Mail::send(new NewsTipMailer($newsTip, $recipientEmail));
      }
    }

    return redirect()->back()->with('message', 'Your news tip has been submitted successfully.');
    } catch (ValidationException $e) {
      // Log the validation error
      Log::error('Validation Error: ', ['errors' => $e->errors()]);

      // Redirect back with validation errors and input
      return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (DecryptException $e) {
      // Log the decryption error
      Log::error('Decryption Error: ', ['exception' => $e]);

      // Redirect back with a decryption error message
      return redirect()->back()->with('error', 'An error occurred while processing your data. Please try again later.')->withInput();
    } catch (\Exception $e) {
      // Log the general error
      Log::error('Error submitting news tip: ', ['request' => $request->all(), 'exception' => $e]);

      // Redirect back with a generic error message
      return redirect()->back()->with('error', 'An error occurred while submitting your news tip. Please try again later.')->withInput();
    }
  }

  /**
   * Create and broadcast a message for a NewsPerson.
   */
  protected function createAndBroadcastNewsPersonMessage(array $data, NewsTip $newsTip): void {

    try {
    // Prepare the combined message in HTML
    $combinedMessage = "<strong>Name:</strong> " . Crypt::decryptString($newsTip->name) . "<br>" .
        "<strong>Email:</strong> " . Crypt::decryptString($newsTip->email) . "<br>" .
        "<strong>Phone:</strong> " . Crypt::decryptString($newsTip->phone) . "<br>" .
        "<strong>Message:</strong> " . nl2br(Crypt::decryptString($newsTip->message)) . "<br><br>" .
        "<strong>Postal Code:</strong> " . Crypt::decryptString($newsTip->postalCode);

    $data['recipient_id'] = $data['news_person_id'];

    // Encrypt the subject and message
    $data['subject'] = Crypt::encryptString('News Tip Received');
    $data['message'] = Crypt::encryptString($combinedMessage);

    // Create the message
    NewsPersonMessage::create($data);

    // Broadcast the event
    broadcast(new NewNewsPersonMessage($data['news_person_id']))->toOthers();
    } catch (DecryptException $e) {
      // Log the decryption error
      Log::error('Decryption Error: ', ['exception' => $e]);
    } catch (\Exception $e) {
      // Log the general error
      Log::error('Error creating and broadcasting news person message: ', ['data' => $data, 'exception' => $e]);
    }
  }

}

