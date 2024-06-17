<?php

namespace App\Http\Controllers;

use App\Mail\EventReminderMailer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailReminderController extends Controller
{
  public function sendEmailReminder(Request $request): \Illuminate\Http\JsonResponse {
    $eventDetails = $request->input('eventDetails');
    $recipientEmail = $request->input('email');

    $broadcastDate = Carbon::parse($eventDetails['broadcastDate'])->subMinutes(30)->setTimezone('UTC');
    //    For testing, sends an email in 1 minute from now:
    //    $broadcastDate = Carbon::now()->addMinutes(1)->setTimezone('UTC');

//    Log::debug('incoming date: ' . $eventDetails['broadcastDate']);
//    Log::debug('converted date: ' . $broadcastDate);


    // Schedule the email
    Mail::to($recipientEmail)->later($broadcastDate, new EventReminderMailer($eventDetails, $recipientEmail));

    return response()->json(['message' => 'Email reminder scheduled successfully.']);
  }
}
