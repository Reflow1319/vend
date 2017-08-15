<?php

use App\Notifications\NotificationRead;
use App\Notifications\Notify;

if (!function_exists('notify')) {
    /**
     * Creates a new Notify instance.
     *
     * @param $params
     *
     * @return Notify
     */
    function notify($params)
    {
        return new Notify($params);
    }
}

if (!function_exists('notifyRead')) {
    /**
     * Creates a new Notify instance.
     *
     * @param $type
     * @param $ids
     *
     * @return NotificationRead
     */
    function notifyRead($type, $ids)
    {
        return new NotificationRead($type, $ids);
    }
}
