<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    private $apiKey;
    private $apiUrl = 'http://www.omdbapi.com/';

    public function __construct()
    {
        $this->middleware('auth');
        $this->apiKey = env('OMDB_API_KEY');
    }

    /**
     * Display a listing of movies.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->searchMovies($request);
        }

        return view('movies.index');
    }

    /**
     * Search movies from OMDB API.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMovies(Request $request)
    {
        $search = $request->input('s', 'movie');
        $type = $request->input('type', '');
        $year = $request->input('y', '');
        $page = $request->input('page', 1);

        $params = [
            'apikey' => $this->apiKey,
            's' => $search,
            'page' => $page,
            'r' => 'json', // Explicitly request JSON response
        ];

        if ($type) {
            $params['type'] = $type;
        }

        if ($year) {
            $params['y'] = $year;
        }

        try {
            $url = $this->apiUrl . '?' . http_build_query($params);

            // Create stream context for better error handling
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'ignore_errors' => true,
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response === false) {
                return response()->json([
                    'Response' => 'False',
                    'Error' => 'Failed to connect to OMDB API. Please check your internet connection and API key.',
                ], 500);
            }

            $data = json_decode($response, true);

            if (!$data) {
                return response()->json([
                    'Response' => 'False',
                    'Error' => 'Invalid response from OMDB API',
                ], 500);
            }

            // Get user favorites for marking
            /** @var \App\User $user */
            $user = auth()->user();
            $userFavorites = $user->favorites()->pluck('imdb_id')->toArray();

            if (isset($data['Search'])) {
                foreach ($data['Search'] as &$movie) {
                    $movie['is_favorite'] = in_array($movie['imdbID'], $userFavorites);
                }
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'Response' => 'False',
                'Error' => 'Failed to fetch movies: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified movie.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $params = [
                'apikey' => $this->apiKey,
                'i' => $id,
                'plot' => 'full',
                'r' => 'json', // Explicitly request JSON response
            ];

            $url = $this->apiUrl . '?' . http_build_query($params);

            // Create stream context for better error handling
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'ignore_errors' => true,
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response === false) {
                abort(500, 'Failed to connect to OMDB API. Please check your API key.');
            }

            $movie = json_decode($response, true);

            if (!$movie) {
                abort(500, 'Invalid response from OMDB API');
            }

            if (isset($movie['Response']) && $movie['Response'] === 'False') {
                abort(404, $movie['Error'] ?? 'Movie not found');
            }

            // Check if movie is in favorites
            /** @var \App\User $user */
            $user = auth()->user();
            $isFavorite = $user->favorites()
                ->where('imdb_id', $id)
                ->exists();

            return view('movies.show', compact('movie', 'isFavorite'));
        } catch (\Exception $e) {
            \Log::error('Failed to fetch movie: ' . $e->getMessage());
            abort(500, 'Failed to fetch movie details');
        }
    }
}
