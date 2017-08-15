<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['favoritable_type'];

    protected $appends = ['type'];

    const types = [
        'topics'   => 'App\Topic',
        'projects' => 'App\Project',
    ];

    /**
     * Alter the favoriteable_type to type name.
     *
     * @return mixed
     */
    public function getTypeAttribute()
    {
        return array_search($this->attributes['favoritable_type'], self::types);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favoritable()
    {
        return $this->morphTo();
    }
}
