<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "web" middleware group. Enjoy building your API!
|
*/

// Home route - redirect to movies if authenticated, otherwise to login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('movies.index') : redirect()->route('login');
})->name('home');

// Authentication Routes
Route::get('/login', 'AuthController@showLogin')->name('login');
Route::post('/login', 'AuthController@login')->name('login.post');
Route::post('/logout', 'AuthController@logout')->name('logout');

// Movie Routes (requires authentication via middleware in controller)
Route::get('/movies', 'MovieController@index')->name('movies.index');
Route::get('/movies/search', 'MovieController@searchMovies')->name('movies.search');
Route::get('/movies/{id}', 'MovieController@show')->name('movies.show');

// Favorite Routes (requires authentication via middleware in controller)
Route::get('/favorites', 'FavoriteController@index')->name('favorites.index');
Route::post('/favorites', 'FavoriteController@store')->name('favorites.store');
Route::delete('/favorites/{imdbId}', 'FavoriteController@destroy')->name('favorites.destroy');

// Language switching route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/test-omdb', function () {

    $apiKey = env('OMDB_API_KEY');

    $url = "https://www.omdbapi.com/?apikey={$apiKey}&s=batman";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return response()->json([
        'api_key' => $apiKey,
        'http_code' => $httpCode,
        'error' => $error,
        'response_sample' => substr($response, 0, 500)
    ]);
});
