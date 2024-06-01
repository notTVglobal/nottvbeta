<?php

namespace App\Http\Controllers;

use App\Events\NewNewsPersonMessage;
use App\Mail\NewsTipMailer;
use App\Mail\PressReleaseMailer;
use App\Models\AppSetting;
use App\Models\NewsPerson;
use App\Models\NewsPersonMessage;
use App\Models\NewsPressRelease;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NewsPressReleaseController extends Controller {
  public function submitPressRelease(Request $request): \Illuminate\Http\RedirectResponse {
    // Validate the request input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // max 10MB
    ]);

    // Encrypt the name and email
    $validatedData['name'] = e(Crypt::encryptString($validatedData['name']));
    $validatedData['email'] = e(Crypt::encryptString($validatedData['email']));

    // Retrieve cloud_folder and cdn_endpoint from app_settings
    $appSettings = AppSetting::where('id', 1)->first(['cloud_folder', 'cdn_endpoint']);
    $cloud_folder = $appSettings->cloud_folder;
    $cdn_endpoint = $appSettings->cdn_endpoint;
    $folder = Carbon::now()->format('/Y/m') . '/docs';

    // Store the file temporarily using the scoped disk
    $tempPath = $request->file('file')->store('', 'temp-files');
    $fullTempPath = storage_path('app/public/temp-files/' . $tempPath);
    Log::info("Temporary file path: $fullTempPath");

    // Ensure the file exists at the expected location
    if (file_exists($fullTempPath)) {
      Log::info("Temporary file found at path: $fullTempPath");
    } else {
      Log::error("Temporary file not found at path: $fullTempPath");
      return redirect()->back()->withErrors(['file' => 'The file could not be uploaded. Please try again.']);
    }

    // Perform virus scan
    $scanResult = shell_exec("clamscan " . escapeshellarg($fullTempPath) . " 2>&1");
    Log::info("ClamAV scan result: $scanResult");

    if (str_contains($scanResult, 'FOUND')) {
      Log::warning("Virus detected in file: {$request->file('file')->getClientOriginalName()}. File will be deleted.");
      unlink($fullTempPath);
      return redirect()->back()->withErrors(['file' => 'A virus was detected in your uploaded file. The file has been deleted.']);
    }

    // Ensure the temp file still exists before attempting to store in cloud
    if (!file_exists($fullTempPath)) {
      Log::error("Temporary file not found before cloud storage: $fullTempPath");
      return redirect()->back()->withErrors(['file' => 'The file could not be processed. Please try again.']);
    }

    // Store the file in the cloud storage and log the cloud path
    $filePath = Storage::disk('spaces')->putFileAs($cloud_folder . $folder, new File($fullTempPath), $request->file('file')->getClientOriginalName());
    Log::info("File stored at: $filePath");

    // Delete the temporary file
    Storage::disk('temp-files')->delete($tempPath);
    Log::info("Temporary file deleted: $fullTempPath");

    // Create the press release entry in the database
    NewsPressRelease::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'file_path' => $filePath,
    ]);

    // Fetch all news_people with roles 6 or 8
    $newsPeople = NewsPerson::whereHas('roles', function ($query) {
      $query->whereIn('news_people_roles.id', [6, 8]);
    })->get();

    foreach ($newsPeople as $newsPerson) {
      $recipientEmail = $newsPerson->user->email ?? null;
      if ($recipientEmail) {
        $this->createAndBroadcastNewsPersonMessage(array_merge($validatedData, ['news_person_id' => $newsPerson->id]), $filePath);
        // Send notification email
        Mail::send(new PressReleaseMailer($filePath, $recipientEmail));
      }
    }

    return redirect()->back()->with('message', 'Your press release has been submitted successfully.');
  }

  /**
   * Create and broadcast a message for a NewsPerson.
   */
  protected function createAndBroadcastNewsPersonMessage(array $data, string $filePath) {
    // Prepare the combined message
    $combinedMessage = "<strong>Name:</strong> " . e(Crypt::decryptString($data['name'])) . "<br>" .
        "<strong>Email:</strong> " . e(Crypt::decryptString($data['email'])) . "<br>" .
        "<strong>File:</strong> " . e($filePath);

    $data['recipient_id'] = $data['news_person_id'];

    // Encrypt the subject and message
    $data['subject'] = Crypt::encryptString('Press Release Received');
    $data['message'] = Crypt::encryptString($combinedMessage);

    // Create the message
    NewsPersonMessage::create($data);

    // Broadcast the event
    broadcast(new NewNewsPersonMessage($data['news_person_id']))->toOthers();
  }
}
