# OMDB Movie Application

A Laravel 5-based web application for browsing movies using the OMDB API, with features including authentication, movie search with filters, favorites management, infinite scroll, lazy loading, and multi-language support (English/Indonesian).

![OMDB Movie App](https://via.placeholder.com/800x400?text=OMDB+Movie+App)

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Architecture](#architecture)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Libraries Used](#libraries-used)

## ✨ Features

### Core Features
- **User Authentication**: Secure login system with credential validation
- **Movie Browsing**: Search and browse movies from OMDB API
- **Advanced Search**: Filter by title, type (movie/series/episode), and year
- **Movie Details**: Comprehensive movie information display
- **Favorites Management**: Add and remove favorite movies
- **Infinite Scroll**: Seamless loading of more movies as you scroll
- **Lazy Loading**: Optimized image loading for better performance
- **Multi-Language Support**: Switch between English and Indonesian
- **Responsive Design**: Mobile-friendly interface

### Technical Features
- MVC Architecture Pattern
- RESTful API integration
- AJAX-based interactions
- Session-based authentication
- Database relationship management
- CSRF protection
- Clean and maintainable code structure

## 🛠 Technology Stack

### Backend
- **PHP 7.2+**: Server-side programming language
- **Laravel 5.8**: PHP web application framework
- **MySQL**: Relational database management system

### Frontend
- **HTML5/CSS3**: Markup and styling
- **JavaScript (ES6+)**: Client-side scripting
- **jQuery 3.6.0**: JavaScript library for DOM manipulation
- **Bootstrap 5.1.3**: CSS framework for responsive design
- **Font Awesome 6.0**: Icon library

### APIs
- **OMDB API**: Movie database API for retrieving movie information

## 🏗 Architecture

### Design Pattern: MVC (Model-View-Controller)

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Routes    │ ◄── web.php
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Controllers │ ◄── AuthController, MovieController, FavoriteController
└──────┬──────┘
       │
       ├─────────────┐
       ▼             ▼
┌──────────┐   ┌──────────┐
│  Models  │   │   Views  │
└────┬─────┘   └──────────┘
     │
     ▼
┌──────────┐
│ Database │
└──────────┘
```

### Application Structure

```
omdb-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Authentication logic
│   │   │   ├── MovieController.php     # Movie browsing & search
│   │   │   └── FavoriteController.php  # Favorites management
│   │   └── Middleware/
│   │       └── SetLocale.php           # Language switching
│   ├── User.php                        # User model
│   └── Favorite.php                    # Favorite model
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   └── create_favorites_table.php
│   └── seeds/
│       └── UserSeeder.php              # Test user seeder
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php           # Main layout template
│   │   ├── auth/
│   │   │   └── login.blade.php         # Login page
│   │   ├── movies/
│   │   │   ├── index.blade.php         # Movie list
│   │   │   └── show.blade.php          # Movie details
│   │   └── favorites/
│   │       └── index.blade.php         # Favorites list
│   └── lang/
│       ├── en/messages.php             # English translations
│       └── id/messages.php             # Indonesian translations
└── routes/
    └── web.php                         # Application routes
```

## 📦 Installation

### Prerequisites
- PHP >= 7.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (optional, for asset compilation)

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd omdb-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=omdb_app
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Add OMDB API Key**
   
   Register at [http://www.omdbapi.com/](http://www.omdbapi.com/) to get your free API key.
   
   Add to `.env`:
   ```env
   OMDB_API_KEY=your_api_key_here
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed database with test user**
   ```bash
   php artisan db:seed --class=UserSeeder
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    
    Open browser and navigate to: `http://localhost:8000`

## ⚙ Configuration

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="OMDB Movie App"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=omdb_app
DB_USERNAME=root
DB_PASSWORD=

# OMDB API Configuration
OMDB_API_KEY=your_api_key_here
```

### Default User Credentials

```
Username: aldmic
Password: 123abc123
```

## 🚀 Usage

### Login
1. Navigate to the application URL
2. Enter credentials (aldmic / 123abc123)
3. Click "Login" button

### Browse Movies
1. Use the search form to filter movies:
   - **Title**: Enter movie name
   - **Type**: Select movie, series, or episode
   - **Year**: Filter by release year
2. Scroll down to load more results automatically (infinite scroll)
3. Click "View Details" to see full movie information

### Manage Favorites
1. Click heart icon on any movie card to add to favorites
2. Navigate to "My Favorites" from the menu
3. Click trash icon to remove from favorites
4. View details by clicking "View Details" button

### Switch Language
1. Click "EN" or "ID" button in the navigation bar
2. Interface will change to selected language immediately

## 📸 Screenshots

### Login Page
![Login Page](https://via.placeholder.com/800x500?text=Login+Page+-+Clean+and+Professional)

### Movie List with Search
![Movie List](https://via.placeholder.com/800x500?text=Movie+List+-+Infinite+Scroll+%26+Lazy+Load)

### Movie Details
![Movie Details](https://via.placeholder.com/800x500?text=Movie+Details+-+Comprehensive+Information)

### My Favorites
![Favorites](https://via.placeholder.com/800x500?text=My+Favorites+-+Easy+Management)

### Multi-Language Support
![Multi-Language](https://via.placeholder.com/800x500?text=English+%2F+Indonesian+Support)

## 📚 Libraries Used

### Backend Libraries
| Library | Version | Purpose |
|---------|---------|---------|
| Laravel Framework | 5.8.* | PHP web application framework |
| Guzzle HTTP | ^6.3 | HTTP client for API requests |
| Laravel Tinker | ^1.0 | REPL for Laravel |
| PHPUnit | ^7.5 | Testing framework |

### Frontend Libraries
| Library | Version | Purpose |
|---------|---------|---------|
| Bootstrap | 5.1.3 | CSS framework for responsive design |
| jQuery | 3.6.0 | DOM manipulation and AJAX |
| Font Awesome | 6.0.0 | Icon library |
| Intersection Observer API | Native | Lazy loading implementation |

### Key Features Implementation

#### 1. Infinite Scroll
```javascript
// Using jQuery scroll event with threshold
$(window).on('scroll', function() {
    if ($(window).scrollTop() + $(window).height() > $(document).height() - 500) {
        if (!isLoading && hasMorePages) {
            currentPage++;
            loadMovies();
        }
    }
});
```

#### 2. Lazy Loading
```javascript
// Using Intersection Observer API
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            observer.unobserve(img);
        }
    });
});
```

#### 3. Multi-Language Support
```php
// SetLocale Middleware
public function handle($request, Closure $next)
{
    $locale = session('locale', 'en');
    App::setLocale($locale);
    return $next($request);
}
```

## 🔒 Security Features

- CSRF Protection on all forms
- Password hashing using bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection through Blade templating
- Session-based authentication
- Input validation and sanitization

## 🎯 Code Quality Standards

### Naming Conventions
- **Controllers**: PascalCase with "Controller" suffix (e.g., `MovieController`)
- **Models**: PascalCase singular (e.g., `Favorite`)
- **Methods**: camelCase (e.g., `loadMovies()`)
- **Variables**: camelCase (e.g., `$currentPage`)
- **Routes**: kebab-case (e.g., `/my-favorites`)
- **Views**: kebab-case (e.g., `movie-details.blade.php`)

### Code Organization
- Single Responsibility Principle followed
- DRY (Don't Repeat Yourself) principle applied
- Proper indentation (4 spaces)
- Meaningful comments where necessary
- Consistent file structure

## 🐛 Troubleshooting

### Common Issues

**Issue: "Class 'App\Http\Middleware\SetLocale' not found"**
- Solution: Run `composer dump-autoload`

**Issue: API requests failing**
- Solution: Check OMDB_API_KEY in .env file
- Verify API key is valid at omdbapi.com

**Issue: Images not loading**
- Solution: Check browser console for CORS issues
- Ensure OMDB API is accessible

**Issue: Database connection error**
- Solution: Verify MySQL is running
- Check database credentials in .env

## 📝 License

This project is open-sourced software for educational and evaluation purposes.

## 👨‍💻 Developer Notes

### Development Best Practices Applied
- ✅ Clean and readable code
- ✅ Proper error handling
- ✅ Security best practices
- ✅ Responsive design
- ✅ Performance optimization
- ✅ SEO-friendly structure
- ✅ Accessibility considerations

### Future Enhancement Ideas
- User registration system
- Movie ratings and reviews
- Social sharing features
- Watchlist functionality
- Advanced filtering options
- Movie recommendations
- Dark mode theme

---

**Developed as part of Web Developer Technical Test**

For questions or support, please contact the development team.

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
