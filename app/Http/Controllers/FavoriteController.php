<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Project;
use App\Topic;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @var array
     */
    protected $types = [
        'topics' => Topic::class,
        'projects' => Project::class
    ];

    /**
     * Get favorites
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = Favorite::whereUserId(Auth::user()->id)
            ->with('favoritable')
            ->get();

        return response()->make($favorites);
    }

    /**
     * Creates a favorite by type and id
     *
     * @param $type
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function save($type, $id)
    {
        $model = $this->getModel($type, $id);

        $favorite = $model->favorites()->firstOrNew([
            'user_id' => Auth::user()->id,
        ]);

        if( ! $favorite->exists) {
            $favorite = $model->favorites()->save($favorite);
            $favorite->load('favoritable');
        }

        return response()->make($favorite);
    }

    /**
     * Deletes a favorite by type and id
     *
     * @param $type
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($type, $id)
    {
        $model = $this->getModel($type, $id);

        $favorite = $model->favorites()->firstOrNew([
            'user_id' => Auth::user()->id,
        ]);

        if($favorite->exists) {
            $favorite->delete();
        }

        return response()->make(null, 204);
    }

    /**
     * Returns model based on type and id
     *
     * @param $type
     * @param $id
     *
     * @return null
     */
    private function getModel($type, $id)
    {
        if (array_key_exists($type, $this->types)) {
            return (new $this->types[$type])->find($id);
        }

        return null;
    }
}
