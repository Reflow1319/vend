<?php

namespace App\Notifications;

use App\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationRead
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $notifications;

    /**
     * NotificationRead constructor.
     *
     * @param $type
     * @param array $id
     */
    public function __construct($type = null, array $id = [])
    {
        if (is_null($type) && empty($id)) {
            $this->notifications = Notification::get();
        } else {
            $this->notifications = Notification::where('related_type', $type)
                ->whereIn('related_id', $id)
                ->get();
        }
    }

    /**
     * Mark notifications as read.
     */
    public function read()
    {
        foreach ($this->notifications as $notification) {
            $notification
                ->users()
                ->updateExistingPivot(Auth::user()->id, [
                    'read_at' => Carbon::now(),
                ]);
        }
    }
}
