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

}
