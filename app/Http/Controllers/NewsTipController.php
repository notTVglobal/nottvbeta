<?php

namespace App\Http\Controllers;

use App\Events\NewNewsPersonMessage;
use App\Mail\NewsTipMailer;
use App\Models\NewsPerson;
use App\Models\NewsPersonMessage;
use App\Models\NewsTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class NewsTipController extends Controller {
  public function submitNewsTip(Request $request) {
    // Validate the request input
    $validatedData = $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|max:255',
        'postalCode'     => 'nullable|string|max:255',
        'message'        => 'required|string|max:5000',
        'news_person_id' => 'nullable|exists:news_people,id',
    ]);

    // Escape and Encrypt the input data
    $escapedName = e($validatedData['name']);
    $escapedEmail = e($validatedData['email']);
    $escapedMessage = e($validatedData['message']);
    $escapedPostalCode = isset($validatedData['postalCode']) ? e($validatedData['postalCode']) : null;

    $encryptedName = Crypt::encryptString($escapedName);
    $encryptedEmail = Crypt::encryptString($escapedEmail);
    $encryptedMessage = Crypt::encryptString($escapedMessage);
    $encryptedPostalCode = $escapedPostalCode ? Crypt::encryptString($escapedPostalCode) : null;

    // Prepare the validated data for creating the news tip
    $validatedData['name'] = $encryptedName;
    $validatedData['email'] = $encryptedEmail;
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
  }

  /**
   * Create and broadcast a message for a NewsPerson.
   */
  protected function createAndBroadcastNewsPersonMessage(array $data, NewsTip $newsTip)
  {
    // Prepare the combined message in HTML
    $combinedMessage = "<strong>Name:</strong> " . Crypt::decryptString($newsTip->name) . "<br>" .
        "<strong>Email:</strong> " . Crypt::decryptString($newsTip->email) . "<br>" .
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
  }

}

