<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'start', 'end', 'location'];

    public function getStartAttribute()
    {
        return Carbon::parse($this->attributes['start'])->format('Y-m-d H:i');
    }

    public function getEndAttribute()
    {
        return $this->attributes['end']
            ? Carbon::parse($this->attributes['end'])->format('Y-m-d H:i')
            : null;
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * @param null $hash
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static|static[]
     */
    public static function getAllEvents($hash = null)
    {
        $user = null;
        if ($hash) {
            $user = User::whereEventUrl($hash)->first();
            if (!$user) {
                throw new NotFoundHttpException();
            }
        }

        // Get from db
        $events = self::orderBy('start')->get();

        // Add type to events
        $events = collect($events->map(function ($event) {
            $event->type = 'event';

            return $event;
        })->toArray());

        $events = $events->merge(Project::events());
        $events = $events->merge(Card::events());

        return $events;
    }
}
