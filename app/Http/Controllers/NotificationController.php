<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Notifications\NotificationRead;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $notifications = Notification::select('notifications.*', 'notification_user.read_at as read_at')
            ->where('notification_user.user_id', Auth::user()->id)
            ->leftJoin('notification_user', 'notifications.id', '=', 'notification_id')
            ->with('actor')
            ->groupBy('notifications.id')
            ->latest()
            ->paginate(50);

        return response()->make($notifications);
    }

    /**
     * Mark all notifications as read.
     *
     * @param string|null $type
     * @param int|null    $id
     *
     * @return \Illuminate\Http\Response
     */
    public function read($type = null, $id = null)
    {
        (new NotificationRead($type, (array) $id))->read();

        return response()->make(null, 204);
    }
}
