<?php

use App\Notifications\Notify;

if ( ! function_exists('notify')) {
    /**
     * Creates a new Notify instance
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