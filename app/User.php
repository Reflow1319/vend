<?php

namespace App;

use App\Traits\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable
        = [
            'name',
            'email',
            'password',
            'image',
            'role',
            'event_url',
        ];

    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->event_url = Str::random(24);
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function isEditor()
    {
        return $this->role === 'editor' || $this->role === 'admin';
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image'] === null) {
            return asset('images/profile.png');
        }

        return $this->attributes['image'];
    }

    public function getFavorite($type, $id)
    {
        return $this->favorites()
            ->where('favoritable_type', Favorite::types[$type])
            ->where('favoritable_id', $id)
            ->first();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class)->whereUserId(Auth::user()->id);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class)->orderBy('updated_at', 'desc');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
