<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of favorite movies.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\User $user */
        $user = Auth::user();
        $favorites = $user->favorites()->latest()->get();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Store a newly created favorite.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'imdb_id' => 'required|string',
            'title' => 'required|string',
            'poster' => 'nullable|string',
        ]);

        // Check if already exists
        /** @var \App\User $user */
        $user = Auth::user();
        $exists = $user->favorites()
            ->where('imdb_id', $request->imdb_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => __('messages.already_in_favorites'),
            ], 409);
        }

        /** @var \App\User $user */
        $user = Auth::user();
        $favorite = $user->favorites()->create([
            'imdb_id' => $request->imdb_id,
            'title' => $request->title,
            'poster' => $request->poster,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.added_to_favorites'),
            'favorite' => $favorite,
        ]);
    }

    /**
     * Remove the specified favorite.
     *
     * @param string $imdbId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($imdbId)
    {
        /** @var \App\User $user */
        $user = Auth::user();
        $favorite = $user->favorites()
            ->where('imdb_id', $imdbId)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => __('messages.favorite_not_found'),
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => __('messages.removed_from_favorites'),
        ]);
    }
}
