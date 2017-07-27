<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::whereUserId(Auth::user()->id)
            ->with('favoritable')
            ->get();

        return response()->make($favorites);
    }
}
