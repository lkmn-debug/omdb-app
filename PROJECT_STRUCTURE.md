# Project Structure - OMDB Movie App

Detailed breakdown of the application architecture and file organization.

## Directory Structure

```
omdb-app/
│
├── app/                                # Application core
│   ├── Console/                        # Console commands
│   │   └── Kernel.php
│   ├── Exceptions/                     # Exception handlers
│   │   └── Handler.php
│   ├── Http/                          # HTTP layer
│   │   ├── Controllers/               # Controllers
│   │   │   ├── AuthController.php     # Authentication logic
│   │   │   ├── FavoriteController.php # Favorites management
│   │   │   └── MovieController.php    # Movie browsing & search
│   │   ├── Middleware/                # Middleware
│   │   │   ├── Authenticate.php
│   │   │   ├── SetLocale.php         # Language switching
│   │   │   └── ...
│   │   └── Kernel.php                 # HTTP kernel
│   ├── Providers/                     # Service providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   └── ...
│   ├── Favorite.php                   # Favorite model
│   └── User.php                       # User model
│
├── bootstrap/                          # Framework bootstrap
│   ├── app.php
│   └── cache/
│
├── config/                            # Configuration files
│   ├── app.php                        # App configuration
│   ├── auth.php                       # Authentication config
│   ├── database.php                   # Database config
│   └── ...
│
├── database/                          # Database files
│   ├── factories/                     # Model factories
│   │   └── UserFactory.php
│   ├── migrations/                    # Database migrations
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2014_10_12_100000_create_password_resets_table.php
│   │   └── 2026_02_20_134551_create_favorites_table.php
│   └── seeds/                         # Database seeders
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php             # Test user seeder
│
├── public/                            # Public assets
│   ├── index.php                      # Application entry point
│   ├── css/
│   ├── js/
│   └── ...
│
├── resources/                         # Resources and views
│   ├── js/                           # JavaScript files
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   └── components/
│   ├── lang/                         # Language files
│   │   ├── en/                       # English
│   │   │   ├── auth.php
│   │   │   └── messages.php
│   │   └── id/                       # Indonesian
│   │       ├── auth.php
│   │       └── messages.php
│   ├── sass/                         # SASS files
│   │   ├── app.scss
│   │   └── _variables.scss
│   └── views/                        # Blade templates
│       ├── layouts/
│       │   └── app.blade.php         # Main layout
│       ├── auth/
│       │   └── login.blade.php       # Login page
│       ├── movies/
│       │   ├── index.blade.php       # Movie list
│       │   └── show.blade.php        # Movie details
│       └── favorites/
│           └── index.blade.php       # Favorites list
│
├── routes/                           # Route definitions
│   ├── api.php                       # API routes
│   ├── channels.php                  # Broadcast channels
│   ├── console.php                   # Console routes
│   └── web.php                       # Web routes
│
├── storage/                          # Storage directory
│   ├── app/                          # Application files
│   ├── framework/                    # Framework cache
│   └── logs/                         # Application logs
│
├── tests/                            # Testing
│   ├── Feature/
│   └── Unit/
│
├── vendor/                           # Composer dependencies
│
├── .env                              # Environment variables
├── .env.example                      # Environment template
├── artisan                           # Artisan CLI
├── composer.json                     # PHP dependencies
├── package.json                      # Node dependencies
├── phpunit.xml                       # PHPUnit config
├── webpack.mix.js                    # Laravel Mix config
│
└── Documentation/
    ├── README.md                     # Main documentation
    ├── INSTALLATION_GUIDE.md         # Setup instructions
    ├── API_DOCUMENTATION.md          # API reference
    ├── TESTING_CHECKLIST.md          # Testing guide
    └── PROJECT_STRUCTURE.md          # This file
```

## Key Files Description

### Core Application Files

#### app/Http/Controllers/AuthController.php
```php
- showLogin()    // Display login form
- login()        // Handle login request
- logout()       // Handle logout request
```

#### app/Http/Controllers/MovieController.php
```php
- index()        // Display movie list / handle AJAX search
- searchMovies() // Search movies from OMDB API
- show($id)      // Display movie details
```

#### app/Http/Controllers/FavoriteController.php
```php
- index()           // Display favorites list
- store()           // Add movie to favorites
- destroy($imdbId)  // Remove movie from favorites
```

### Models

#### app/User.php
```php
- favorites()    // Relationship: User has many Favorites
```

#### app/Favorite.php
```php
- user()         // Relationship: Favorite belongs to User
```

### Routes

#### routes/web.php
```php
GET  /                          → Redirect to movies
GET  /login                     → Show login form
POST /login                     → Process login
POST /logout                    → Process logout
GET  /language/{locale}         → Switch language

// Protected routes (require auth)
GET  /movies                    → Movie list
GET  /movies/{id}              → Movie details
GET  /favorites                → Favorites list
POST /favorites                → Add favorite
DELETE /favorites/{imdbId}     → Remove favorite
```

### Views

#### resources/views/layouts/app.blade.php
Master layout with:
- Navigation bar
- Language switcher
- User info & logout
- Bootstrap & jQuery setup
- CSRF token setup
- Toast notifications
- Lazy load functionality

#### resources/views/auth/login.blade.php
Login page with:
- Username input
- Password input
- Remember me checkbox
- Test credentials display

#### resources/views/movies/index.blade.php
Movie list with:
- Search form (title, type, year)
- Movie grid (cards)
- Infinite scroll
- Lazy loading
- Favorite toggle

#### resources/views/movies/show.blade.php
Movie details with:
- Poster
- Full information
- Ratings
- Favorite button
- Back to list button

#### resources/views/favorites/index.blade.php
Favorites with:
- Favorite count badge
- Movie cards
- Delete functionality
- Empty state

### Language Files

#### resources/lang/en/messages.php
English translations for:
- UI labels
- Button text
- Messages
- Errors

#### resources/lang/id/messages.php
Indonesian translations for:
- UI labels
- Button text
- Messages
- Errors

### Database

#### Migration: create_users_table.php
```sql
id (bigint, PK)
name (string)
email (string, unique)
email_verified_at (timestamp, nullable)
password (string)
remember_token (string, nullable)
created_at (timestamp)
updated_at (timestamp)
```

#### Migration: create_favorites_table.php
```sql
id (bigint, PK)
user_id (bigint, FK → users.id)
imdb_id (string)
title (string)
poster (string, nullable)
created_at (timestamp)
updated_at (timestamp)
```

## Architecture Patterns

### MVC Pattern

```
┌──────────────┐
│   Request    │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│   Router     │ (routes/web.php)
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Controller  │ (app/Http/Controllers/)
└──────┬───────┘
       │
       ├─────────────────┐
       │                 │
       ▼                 ▼
┌────────────┐    ┌────────────┐
│   Model    │    │    View    │
│(app/*.php) │    │(resources/ │
│            │    │   views/)  │
└─────┬──────┘    └────────────┘
      │                  │
      ▼                  │
┌────────────┐          │
│  Database  │          │
└────────────┘          │
                        │
       ┌────────────────┘
       │
       ▼
┌──────────────┐
│   Response   │
└──────────────┘
```

### Request Lifecycle

1. **Entry Point**: `public/index.php`
2. **Bootstrap**: Load Laravel framework
3. **Service Providers**: Register services
4. **Middleware**: Process request (auth, locale, CSRF)
5. **Router**: Match URL to controller
6. **Controller**: Handle business logic
7. **Model**: Database operations
8. **View**: Render response
9. **Response**: Send to client

### Authentication Flow

```
┌─────────────┐
│ Login Form  │
└──────┬──────┘
       │ POST credentials
       ▼
┌─────────────────┐
│ AuthController  │
└──────┬──────────┘
       │ Validate
       ▼
┌─────────────────┐
│  Auth Facade    │
└──────┬──────────┘
       │ Check database
       ▼
┌─────────────────┐
│   User Model    │
└──────┬──────────┘
       │ Match?
       ▼
┌─────────────────┐
│  Create Session │
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│  Redirect Home  │
└─────────────────┘
```

### API Integration Flow

```
┌──────────────┐
│   Frontend   │
│   (jQuery)   │
└──────┬───────┘
       │ AJAX Request
       ▼
┌──────────────────┐
│ MovieController  │
└──────┬───────────┘
       │ HTTP Client
       ▼
┌──────────────────┐
│   OMDB API       │
│ (omdbapi.com)    │
└──────┬───────────┘
       │ JSON Response
       ▼
┌──────────────────┐
│ Process & Enhance│
│ (add favorites)  │
└──────┬───────────┘
       │
       ▼
┌──────────────────┐
│  Return JSON     │
└──────────────────┘
```

## Design Patterns Used

### 1. Repository Pattern
- Models abstract database operations
- Controllers don't query database directly

### 2. Dependency Injection
- Laravel's service container manages dependencies
- Controllers receive dependencies via constructor

### 3. Middleware Pattern
- Request processing pipeline
- Authentication, CSRF, locale setting

### 4. Front Controller Pattern
- Single entry point: `public/index.php`
- All requests routed through one controller

### 5. Template View Pattern
- Blade templating engine
- Layout inheritance
- Component reusability

## Security Implementations

### 1. Authentication
- Session-based authentication
- Password hashing (bcrypt)
- Remember me functionality

### 2. Authorization
- Middleware protection
- Route guards
- Policy enforcement (implicit)

### 3. CSRF Protection
- Token on all forms
- Automatic validation
- Token rotation

### 4. Input Validation
- Request validation
- Type casting
- Sanitization

### 5. XSS Prevention
- Blade {{ }} escaping
- Input sanitization
- Content Security Policy ready

### 6. SQL Injection Prevention
- Eloquent ORM
- Parameterized queries
- No raw SQL

## Performance Optimizations

### 1. Frontend
- **Lazy Loading**: Images load on scroll
- **Infinite Scroll**: Paginated loading
- **AJAX**: No full page reloads
- **CDN**: External libraries from CDN

### 2. Backend
- **Eloquent**: Efficient queries
- **Session**: Fast session management
- **Caching**: Ready for implementation

### 3. Database
- **Indexes**: On foreign keys
- **Relationships**: Eager loading ready
- **Migrations**: Version controlled schema

## Code Standards

### Naming Conventions
- **Classes**: PascalCase (UserController)
- **Methods**: camelCase (showLogin)
- **Variables**: camelCase ($userName)
- **Constants**: UPPER_CASE (API_KEY)
- **Routes**: kebab-case (/my-favorites)
- **Views**: kebab-case (movie-list.blade.php)
- **Tables**: snake_case (user_favorites)
- **Columns**: snake_case (user_id)

### File Organization
- One class per file
- Namespaces match directory structure
- Group related functionality

### Code Style
- PSR-2 coding standard
- 4 spaces indentation
- Opening braces on same line
- No trailing whitespace

## Technology Stack Summary

### Backend
- **Framework**: Laravel 5.8
- **Language**: PHP 7.2+
- **Database**: MySQL 5.7+
- **ORM**: Eloquent
- **Templating**: Blade
- **Authentication**: Session-based

### Frontend
- **CSS Framework**: Bootstrap 5.1.3
- **JavaScript Library**: jQuery 3.6.0
- **Icons**: Font Awesome 6.0
- **Build Tool**: Laravel Mix (optional)

### External APIs
- **OMDB API**: Movie data
- **CDNs**: Bootstrap, jQuery, Font Awesome

## Development Tools

### Artisan Commands
```bash
php artisan serve           # Start dev server
php artisan migrate         # Run migrations
php artisan db:seed         # Seed database
php artisan make:controller # Create controller
php artisan make:model      # Create model
php artisan cache:clear     # Clear cache
php artisan route:list      # List all routes
```

### Composer Commands
```bash
composer install            # Install dependencies
composer update            # Update dependencies
composer dump-autoload     # Regenerate autoload
```

## Future Enhancements

### Potential Improvements
1. User registration system
2. Password reset functionality
3. Movie reviews and ratings
4. Watchlist feature
5. Social sharing
6. Advanced search filters
7. Recommendation engine
8. Dark mode theme
9. Progressive Web App (PWA)
10. API rate limiting display

### Scalability Considerations
1. Implement Redis caching
2. Queue system for background jobs
3. Database query optimization
4. Image optimization/CDN
5. API response caching
6. Load balancing ready

---

**Last Updated**: February 21, 2026

**Version**: 1.0.0
