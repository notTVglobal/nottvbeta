<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShortUrlController extends Controller {

  // Method to handle fetching the short URL data for a specific show
  public function show($show): \Illuminate\Http\JsonResponse {

    $shortUrl = ShortUrl::where('show_id', $show->id)
        ->with(['user' => function ($query) {
          $query->select('id', 'name');
        }])
        ->first();

// Ensure that only the user's name is returned, not the full user object
    if ($shortUrl && $shortUrl->user) {
      $shortUrl->user = $shortUrl->user->name;
    }

    if (!$shortUrl) {
      return response()->json(['message' => 'No short URL found for this show.'], 404);
    }

    return response()->json($shortUrl);
  }

  // Method to create a new short URL
  public function store(Request $request): \Illuminate\Http\JsonResponse {
    try {
      // Validate the incoming request
      $validated = $request->validate([
          'custom_name'  => 'required|unique:short_urls,custom_name',
          'original_url' => 'required|url',
          'show_id'      => 'nullable|exists:shows,id',
      ]);

      $sanitizedUrl = filter_var($validated['original_url'], FILTER_SANITIZE_URL);

      // Create the short URL
      $shortUrl = ShortUrl::create([
          'identifier'   => 'r', // Identifier is set to 'r'
          'custom_name'  => $validated['custom_name'],
          'original_url' => $sanitizedUrl,
          'user_id'      => auth()->id(), // Assuming user authentication
          'show_id'      => $validated['show_id'],
      ]);

      return response()->json([
          'short_url' => url($shortUrl->identifier . '/' . $shortUrl->custom_name),
      ]);
    } catch (ValidationException $e) {
      // Return validation error messages
      return response()->json([
          'message' => 'Validation failed.',
          'errors' => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      // Log the exception for debugging
      \Log::error('Error creating short URL: ' . $e->getMessage(), [
          'request' => $request->all(),
          'user_id' => auth()->id(),
      ]);

      // Return a more detailed error response
      return response()->json([
          'message' => 'Failed to create the short URL.',
          'error' => $e->getMessage(),
      ], 500);
    }
  }

  // Method to reset the clicks count
  public function resetClicks($show): \Illuminate\Http\JsonResponse
  {
    $shortUrl = ShortUrl::where('show_id', $show->id)->firstOrFail();

    // Reset the clicks to 0
    $shortUrl->update(['clicks' => 0]);

    return response()->json(['clicks' => 0]);
  }

  // Method to toggle the active status
  public function toggleActive($show): \Illuminate\Http\JsonResponse
  {
    $shortUrl = ShortUrl::where('show_id', $show->id)->firstOrFail();

    // Toggle the active status
    $shortUrl->is_active = !$shortUrl->is_active;
    $shortUrl->save();

    return response()->json(['is_active' => $shortUrl->is_active]);
  }

  public function redirect($custom_name): \Illuminate\Http\RedirectResponse
  {
    // Find the short URL by custom_name, assuming identifier is always 'r'
    $shortUrl = ShortUrl::where('identifier', 'r')
        ->where('custom_name', $custom_name)
        ->firstOrFail();

    // Check if the URL is active
    if (!$shortUrl->is_active) {
      abort(404, 'This link is currently inactive. Please check back later or contact the content owner for more details.');
    }

    // Increment the click count
    $shortUrl->increment('clicks');

    // Redirect to the original URL
    return redirect()->away($shortUrl->original_url);
  }
}
