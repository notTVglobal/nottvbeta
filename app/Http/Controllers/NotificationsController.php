<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:delete,notification')->only(['destroy']);
    }

    public function index()
    {

        $user = auth()->user()->id;
        $notifications = Notification::where('user_id', $user)
            ->with(['image.appSetting'])
            ->get();

        return response()->json(['notifications' => $notifications]);
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
}
