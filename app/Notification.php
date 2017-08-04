<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];

    public function getDataAttribute()
    {
        return unserialize($this->attributes['data']);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('read_at');
    }

    public function getTypeAttribute()
    {
        return str_replace(':', '_', $this->attributes['type']);
    }
}
