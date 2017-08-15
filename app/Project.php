<?php

namespace App;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Favoritable;

    protected $fillable = [
            'title',
            'description',
            'is_archive',
            'due_date',
        ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->cards()->delete();
            $model->columns()->delete();
            $model->users()->detach();
        });
    }

    public function columns()
    {
        return $this->hasMany(Column::class)
            ->orderBy('order', 'asc');
    }

    public function completedCards()
    {
        return $this->hasMany(Card::class)->where('is_completed', 1);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function events()
    {
        $projects = self::where('due_date', '!=', null)->get();

        if (empty($projects)) {
            return collect([]);
        }

        return $projects->map(function ($project) {
            return [
                'id'       => $project->id,
                'title'    => $project->title,
                'start'    => $project->due_date,
                'location' => null,
                'end'      => null,
                'type'     => 'project',
            ];
        });
    }
}
