<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:delete,notification')->only(['destroy']);
    }


  public function index()
  {
    try {
      // Fetch and process notifications
      $user = auth()->user();
      if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
      }

      $notifications = Notification::where('user_id', $user->id)
          ->with(['image.appSetting'])
          ->get();

      if ($notifications->isEmpty()) {
        return response()->json(['message' => 'No notifications found for this user.'], 200);
      }

      $notificationResource = NotificationResource::collection($notifications);

      return response()->json(['notifications' => $notificationResource]);
    } catch (\Exception $e) {
      Log::error('Error fetching notifications: ' . $e->getMessage());
      return response()->json(['error' => 'An error occurred while fetching notifications'], 500);
    }
  }


    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function destroy(Notification $notification)
    {
//        $notification = Notification::find(1);
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
        // Ensure the authenticated user owns the notification or has permission to delete it
//        if (auth()->user()->can('delete', $notification)) {
//
//        } else {
//            return response()->json(['error' => 'Unauthorized'], 403);
//        }
    }

    public function destroyAll()
    {
//        $oneMinuteAgo = Carbon::now()->subMinutes(1);
        $tenSecondsAgo = Carbon::now()->subSeconds(10);
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->where('created_at', '<', $tenSecondsAgo)
            ->delete();

        return response()->json(['message' => 'Notifications deleted successfully']);
    }
}
