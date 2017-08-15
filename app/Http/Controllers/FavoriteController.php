<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @var FavoriteService
     */
    private $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    /**
     * Get favorites.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = $this->favoriteService->getUserFavorites(Auth::user()->id);

        return response()->make($favorites);
    }

    /**
     * Creates a favorite by type and id.
     *
     * @param $type
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function save($type, $id)
    {
        $favorite = $this->favoriteService->save($type, $id, Auth::user()->id);

        return response()->make($favorite);
    }

    /**
     * Deletes a favorite by type and id.
     *
     * @param $type
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($type, $id)
    {
        $this->favoriteService->delete($type, $id, Auth::user()->id);

        return response()->make(null, 204);
    }
}
