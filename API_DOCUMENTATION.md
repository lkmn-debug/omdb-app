# API Documentation - OMDB Movie App

This document describes all the API endpoints and routes available in the application.

## Base URL

```
http://localhost:8000
```

---

## Authentication Endpoints

### 1. Show Login Form

**GET** `/login`

Displays the login form.

**Authentication**: Not required

**Response**: HTML login page

**Example**:
```
GET http://localhost:8000/login
```

---

### 2. Login

**POST** `/login`

Authenticates a user and creates a session.

**Authentication**: Not required

**Request Body**:
```json
{
    "username": "aldmic",
    "password": "123abc123",
    "remember": true
}
```

**Success Response (302)**:
- Redirects to `/movies`
- Sets session cookie

**Error Response (422)**:
```json
{
    "errors": {
        "username": [
            "These credentials do not match our records."
        ]
    }
}
```

**Example**:
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"username":"aldmic","password":"123abc123"}'
```

---

### 3. Logout

**POST** `/logout`

Logs out the current user and destroys the session.

**Authentication**: Required

**Response (302)**: Redirects to `/login`

**Example**:
```bash
curl -X POST http://localhost:8000/logout \
  -H "Cookie: laravel_session=..." \
  -H "X-CSRF-TOKEN: ..."
```

---

## Movie Endpoints

### 1. List Movies

**GET** `/movies`

Displays the movies list page or returns movie data (AJAX).

**Authentication**: Required

**Query Parameters**:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| s | string | No | Search query (default: "movie") |
| type | string | No | Movie type: movie, series, episode |
| y | integer | No | Release year |
| page | integer | No | Page number (default: 1) |

**HTML Response**: Movie list page

**AJAX Response (200)**:
```json
{
    "Search": [
        {
            "Title": "Batman Begins",
            "Year": "2005",
            "imdbID": "tt0372784",
            "Type": "movie",
            "Poster": "https://...",
            "is_favorite": false
        }
    ],
    "totalResults": "537",
    "Response": "True"
}
```

**Error Response**:
```json
{
    "Response": "False",
    "Error": "Movie not found!"
}
```

**Examples**:

HTML Request:
```
GET http://localhost:8000/movies
```

AJAX Request:
```javascript
$.ajax({
    url: '/movies',
    data: {
        s: 'Batman',
        type: 'movie',
        y: 2008,
        page: 1
    },
    success: function(data) {
        console.log(data);
    }
});
```

---

### 2. Movie Details

**GET** `/movies/{imdbId}`

Displays detailed information about a specific movie.

**Authentication**: Required

**URL Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| imdbId | string | IMDb ID (e.g., tt0372784) |

**Response**: HTML movie details page

**Movie Data Structure**:
```javascript
{
    "Title": "The Dark Knight",
    "Year": "2008",
    "Rated": "PG-13",
    "Released": "18 Jul 2008",
    "Runtime": "152 min",
    "Genre": "Action, Crime, Drama",
    "Director": "Christopher Nolan",
    "Writer": "Jonathan Nolan, Christopher Nolan",
    "Actors": "Christian Bale, Heath Ledger, Aaron Eckhart",
    "Plot": "When the menace known as the Joker...",
    "Language": "English, Mandarin",
    "Country": "United States, United Kingdom",
    "Awards": "Won 2 Oscars. 159 wins & 163 nominations total",
    "Poster": "https://...",
    "Ratings": [
        { "Source": "Internet Movie Database", "Value": "9.0/10" },
        { "Source": "Rotten Tomatoes", "Value": "94%" }
    ],
    "Metascore": "84",
    "imdbRating": "9.0",
    "imdbVotes": "2,567,890",
    "imdbID": "tt0468569",
    "Type": "movie",
    "DVD": "09 Dec 2008",
    "BoxOffice": "$534,858,444",
    "Production": "Warner Bros. Pictures",
    "Website": "N/A",
    "Response": "True"
}
```

**Example**:
```
GET http://localhost:8000/movies/tt0468569
```

---

## Favorite Endpoints

### 1. List Favorites

**GET** `/favorites`

Displays all favorited movies for the authenticated user.

**Authentication**: Required

**Response**: HTML favorites page

**Example**:
```
GET http://localhost:8000/favorites
```

---

### 2. Add Favorite

**POST** `/favorites`

Adds a movie to the user's favorites.

**Authentication**: Required

**Request Body**:
```json
{
    "imdb_id": "tt0468569",
    "title": "The Dark Knight",
    "poster": "https://..."
}
```

**Success Response (200)**:
```json
{
    "success": true,
    "message": "Movie added to favorites",
    "favorite": {
        "id": 1,
        "user_id": 1,
        "imdb_id": "tt0468569",
        "title": "The Dark Knight",
        "poster": "https://...",
        "created_at": "2026-02-21T10:30:00.000000Z",
        "updated_at": "2026-02-21T10:30:00.000000Z"
    }
}
```

**Error Response (409)** - Already exists:
```json
{
    "success": false,
    "message": "Movie already in favorites"
}
```

**Error Response (422)** - Validation error:
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "imdb_id": ["The imdb id field is required."]
    }
}
```

**Example**:
```javascript
$.ajax({
    url: '/favorites',
    method: 'POST',
    data: {
        imdb_id: 'tt0468569',
        title: 'The Dark Knight',
        poster: 'https://...'
    },
    success: function(response) {
        console.log(response.message);
    }
});
```

---

### 3. Remove Favorite

**DELETE** `/favorites/{imdbId}`

Removes a movie from the user's favorites.

**Authentication**: Required

**URL Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| imdbId | string | IMDb ID (e.g., tt0468569) |

**Success Response (200)**:
```json
{
    "success": true,
    "message": "Movie removed from favorites"
}
```

**Error Response (404)**:
```json
{
    "success": false,
    "message": "Favorite not found"
}
```

**Example**:
```javascript
$.ajax({
    url: '/favorites/tt0468569',
    method: 'DELETE',
    success: function(response) {
        console.log(response.message);
    }
});
```

---

## Utility Endpoints

### 1. Switch Language

**GET** `/language/{locale}`

Switches the application language.

**Authentication**: Not required (but typically used by authenticated users)

**URL Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| locale | string | Language code: 'en' or 'id' |

**Response (302)**: Redirects back to previous page

**Example**:
```
GET http://localhost:8000/language/id
```

---

### 2. Root Redirect

**GET** `/`

Redirects to the movies list or login page.

**Authentication**: Not required

**Response (302)**: 
- If authenticated: Redirects to `/movies`
- If not authenticated: Redirects to `/login`

**Example**:
```
GET http://localhost:8000/
```

---

## Error Responses

### 401 Unauthorized

When accessing protected routes without authentication:

```json
{
    "message": "Unauthenticated."
}
```

Redirects to `/login`

---

### 404 Not Found

When a movie or resource is not found:

```html
<!DOCTYPE html>
<html>
<body>
    <h1>404 - Not Found</h1>
</body>
</html>
```

---

### 419 Page Expired

When CSRF token is invalid or expired:

```html
<!DOCTYPE html>
<html>
<body>
    <h1>419 - Page Expired</h1>
</body>
</html>
```

---

### 500 Internal Server Error

When an unexpected error occurs:

```json
{
    "message": "Server Error"
}
```

---

## AJAX Request Headers

All AJAX requests should include:

```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
    }
});
```

---

## Rate Limiting

OMDB API (external) has the following limits:

- **Free Tier**: 1,000 requests per day
- **Response Time**: Typically < 1 second

---

## OMDB API Integration

The application integrates with OMDB API using the following endpoints:

### Search Movies
```
GET http://www.omdbapi.com/?apikey={key}&s={search}&type={type}&y={year}&page={page}
```

### Get Movie Details
```
GET http://www.omdbapi.com/?apikey={key}&i={imdbId}&plot=full
```

---

## Request Flow

### Movie Search Flow

```
User → Frontend (AJAX) → Laravel Controller → OMDB API
    ← JSON Response ← Process & Enhance ← API Response
```

### Favorite Add Flow

```
User → Frontend (AJAX) → Laravel Controller → Database
    ← JSON Response ← Validation & Save ← Query Result
```

---

## Example Usage with cURL

### Login
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "username=aldmic&password=123abc123" \
  -c cookies.txt
```

### Get Movies (using session)
```bash
curl -X GET "http://localhost:8000/movies?s=Batman&type=movie" \
  -H "X-Requested-With: XMLHttpRequest" \
  -b cookies.txt
```

### Add to Favorites
```bash
curl -X POST http://localhost:8000/favorites \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -b cookies.txt \
  -d '{"imdb_id":"tt0468569","title":"The Dark Knight","poster":"https://..."}'
```

---

## Postman Collection

Import this into Postman for easy testing:

```json
{
    "info": {
        "name": "OMDB Movie App API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Login",
            "request": {
                "method": "POST",
                "url": "{{baseUrl}}/login",
                "body": {
                    "mode": "urlencoded",
                    "urlencoded": [
                        {"key": "username", "value": "aldmic"},
                        {"key": "password", "value": "123abc123"}
                    ]
                }
            }
        },
        {
            "name": "Search Movies",
            "request": {
                "method": "GET",
                "url": {
                    "raw": "{{baseUrl}}/movies?s=Batman&type=movie&page=1",
                    "query": [
                        {"key": "s", "value": "Batman"},
                        {"key": "type", "value": "movie"},
                        {"key": "page", "value": "1"}
                    ]
                }
            }
        }
    ],
    "variable": [
        {
            "key": "baseUrl",
            "value": "http://localhost:8000"
        }
    ]
}
```

---

## Notes

1. All POST, PUT, PATCH, DELETE requests require CSRF token
2. All routes except `/login` and `/` require authentication
3. Session-based authentication is used
4. Cookies must be enabled
5. AJAX requests are automatically detected by checking `X-Requested-With` header

---

**Last Updated**: February 21, 2026
