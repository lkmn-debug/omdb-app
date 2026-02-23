<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    private $apiKey;
    private $apiUrl = 'https://www.omdbapi.com/'; // HTTPS

    public function __construct()
    {
        $this->middleware('auth');
        $this->apiKey = env('OMDB_API_KEY');
    }

    /**
     * Display a listing of movies.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->searchMovies($request);
        }
        return view('movies.index');
    }

    /**
     * Make HTTP request using cURL (more reliable)
     */
    private function makeApiRequest($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'User-Agent: OMDB-Movie-App/1.0'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if (!$response) {
            return [
                'success' => false,
                'error' => 'Connection failed: ' . $error,
                'http_code' => 0
            ];
        }

        return [
            'success' => true,
            'response' => $response,
            'http_code' => $httpCode
        ];
    }

    /**
     * Search movies from OMDB API
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
            'r' => 'json',
        ];

        if ($type) {
            $params['type'] = $type;
        }
        if ($year) {
            $params['y'] = $year;
        }

        try {
            $url = $this->apiUrl . '?' . http_build_query($params);
            
            $result = $this->makeApiRequest($url);

            if (!$result['success']) {
                return response()->json([
                    'Response' => 'False',
                    'Error' => 'Failed to connect to OMDB API: ' . $result['error'],
                ], 500);
            }

            $data = json_decode($result['response'], true);

            if (!$data) {
                return response()->json([
                    'Response' => 'False',
                    'Error' => 'Invalid response from OMDB API',
                ], 500);
            }

            // Add favorites marking
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
     * Display movie details
     */
    public function show($id)
    {
        try {
            $params = [
                'apikey' => $this->apiKey,
                'i' => $id,
                'plot' => 'full',
                'r' => 'json',
            ];

            $url = $this->apiUrl . '?' . http_build_query($params);
            
            $result = $this->makeApiRequest($url);

            if (!$result['success']) {
                abort(500, 'Failed to connect to OMDB API');
            }

            $movie = json_decode($result['response'], true);

            if (!$movie) {
                abort(500, 'Invalid response from OMDB API');
            }

            if (isset($movie['Response']) && $movie['Response'] === 'False') {
                abort(404, $movie['Error'] ?? 'Movie not found');
            }

            // Add favorite status
            $user = auth()->user();
            $isFavorite = $user->favorites()->where('imdb_id', $id)->exists();
            $movie['is_favorite'] = $isFavorite;

            return view('movies.show', ['movie' => $movie]);
        } catch (\Exception $e) {
            abort(500, 'Error fetching movie details: ' . $e->getMessage());
        }
    }
}
