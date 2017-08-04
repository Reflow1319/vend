<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotificationRead;

class NotificationController extends Controller
{
    /**
     * Get notifications
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $notifications = Notification::select('notifications.*', 'notification_user.read_at as read_at')
            ->where('notification_user.user_id' ,Auth::user()->id)
            ->leftJoin('notification_user', 'notifications.id', '=', 'notification_id')
            ->with('actor')
            ->latest()
            ->paginate(50);

        return response()->make($notifications);
    }

    /**
     * Mark all notifications as read
     *
     * @return \Illuminate\Http\Response
     */
    public function read()
    {
        (new NotificationRead())->read();

        return response()->make(null, 204);
    }
}
