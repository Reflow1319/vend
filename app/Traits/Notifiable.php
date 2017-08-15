<?php

namespace App\Traits;

use App\Notification;

trait Notifiable
{
    /**
     * @return mixed
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class);
    }
}
