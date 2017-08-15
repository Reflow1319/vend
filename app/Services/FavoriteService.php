<?php

namespace App\Services;

use App\Favorite;
use App\Project;
use App\Topic;
use Illuminate\Database\Eloquent\Model;

class FavoriteService
{
    /**
     * @var array
     */
    protected $types = [
        'topics'   => Topic::class,
        'projects' => Project::class,
    ];

    /**
     * Get favorites for a given user.
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getUserFavorites($userId)
    {
        return Favorite::whereUserId($userId)
            ->with('favoritable')
            ->get();
    }

    /**
     * Save a favorite.
     *
     * @param $type
     * @param $id
     *
     * @return mixed
     */
    public function save($type, $id, $userId)
    {
        $model = $this->getModel($type, $id);

        $favorite = $model->favorites()->firstOrNew([
            'user_id' => $userId,
        ]);

        if (!$favorite->exists) {
            $favorite = $model->favorites()->save($favorite);
            $favorite->load('favoritable');
        }

        return $favorite;
    }

    /**
     * Deletes a favorite for a user.
     *
     * @param $type
     * @param $id
     * @param $userId
     */
    public function delete($type, $id, $userId)
    {
        $model = $this->getModel($type, $id);

        $favorite = $model->favorites()->firstOrNew([
            'user_id' => $userId,
        ]);

        if ($favorite->exists) {
            $favorite->delete();
        }
    }

    /**
     * Returns model based on type and id.
     *
     * @param $type
     * @param $id
     *
     * @throws \Exception
     *
     * @return Model
     */
    private function getModel($type, $id)
    {
        if (array_key_exists($type, $this->types)) {
            return (new $this->types[$type]())->find($id);
        } else {
            throw new \Exception('Model not found');
        }
    }
}
