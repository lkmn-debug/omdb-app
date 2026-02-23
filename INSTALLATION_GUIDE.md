# Installation Guide - OMDB Movie App

This guide will help you set up the OMDB Movie Application on your local machine.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP**: Version 7.2 or higher
- **Composer**: Latest version
- **MySQL/MariaDB**: Version 5.7 or higher
- **Web Server**: Apache or Nginx (or use PHP built-in server)
- **OMDB API Key**: Free key from [http://www.omdbapi.com/](http://www.omdbapi.com/)

### Check Your PHP Version
```bash
php -v
```

### Check Composer Installation
```bash
composer --version
```

## Installation Steps

### 1. Get the Source Code

If you have the source code in a zip file, extract it. Otherwise, clone the repository:

```bash
# Extract or navigate to the project directory
cd omdb-app
```

### 2. Install PHP Dependencies

Install all required PHP packages using Composer:

```bash
composer install
```

This will install Laravel and all its dependencies.

### 3. Environment Configuration

#### Create Environment File

Copy the example environment file:

```bash
# For Windows (PowerShell)
Copy-Item .env.example .env

# For Windows (Command Prompt)
copy .env.example .env

# For Linux/Mac
cp .env.example .env
```

#### Generate Application Key

```bash
php artisan key:generate
```

This will set the `APP_KEY` in your `.env` file.

### 4. Configure Database

#### Create Database

Open your MySQL client (MySQL Workbench, phpMyAdmin, or command line):

```sql
CREATE DATABASE omdb_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Update .env File

Open the `.env` file in a text editor and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=omdb_app
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Replace `your_mysql_password` with your actual MySQL password.

### 5. Get OMDB API Key

1. Visit [http://www.omdbapi.com/apikey.aspx](http://www.omdbapi.com/apikey.aspx)
2. Select "FREE" option (1,000 daily limit)
3. Enter your email address
4. Check your email for the API key
5. Activate the API key by clicking the link in the email

#### Add API Key to .env

Add your OMDB API key to the `.env` file:

```env
OMDB_API_KEY=your_actual_api_key_here
```

### 6. Run Database Migrations

Create all required database tables:

```bash
php artisan migrate
```

Expected output:
```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2026_02_20_134551_create_favorites_table
Migrated:  2026_02_20_134551_create_favorites_table
```

### 7. Seed Database with Test User

Create the test user account:

```bash
php artisan db:seed --class=UserSeeder
```

This will create a user with:
- **Username**: aldmic
- **Email**: aldmic@example.com
- **Password**: 123abc123

### 8. Start Development Server

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

Alternative port:
```bash
php artisan serve --port=8080
```

## Verification

### Access the Application

1. Open your web browser
2. Navigate to `http://localhost:8000`
3. You should see the login page

### Test Login

Use the following credentials:
- **Username**: aldmic
- **Password**: 123abc123

### Test Movie Search

1. After login, you'll be redirected to the movie list
2. The default search will show results for "movie"
3. Try searching for different movies (e.g., "Batman", "Avengers")
4. Test filters (type, year)

## Troubleshooting

### Issue 1: "Failed to open stream" or Permission Errors

**Solution**: Set proper permissions for storage and bootstrap/cache directories

```bash
# For Linux/Mac
chmod -R 775 storage bootstrap/cache

# For Windows (Run as Administrator in PowerShell)
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T
```

### Issue 2: "Class not found"

**Solution**: Regenerate Composer autoload files

```bash
composer dump-autoload
```

### Issue 3: Database Connection Error

**Symptoms**: 
- "SQLSTATE[HY000] [1045] Access denied"
- "SQLSTATE[HY000] [2002] Connection refused"

**Solutions**:
1. Verify MySQL service is running:
   ```bash
   # Windows (Service)
   net start MySQL80
   
   # Linux
   sudo systemctl start mysql
   ```

2. Check database credentials in `.env`
3. Test MySQL connection:
   ```bash
   mysql -u root -p
   ```

### Issue 4: OMDB API Errors

**Symptoms**:
- Movies not loading
- "Invalid API key" error

**Solutions**:
1. Verify API key is correct in `.env`
2. Ensure API key is activated (check activation email)
3. Check daily limit (1,000 requests for free tier)
4. Test API directly:
   ```
   http://www.omdbapi.com/?apikey=YOUR_KEY&s=movie
   ```

### Issue 5: "419 Page Expired" on Login

**Solution**: Clear cache and regenerate key

```bash
php artisan cache:clear
php artisan config:clear
php artisan key:generate
```

### Issue 6: Blank Page or 500 Error

**Solutions**:
1. Enable debug mode in `.env`:
   ```env
   APP_DEBUG=true
   ```

2. Check Laravel log file:
   ```
   storage/logs/laravel.log
   ```

3. Clear all caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   php artisan route:clear
   ```

## Running in Production

### Important Production Configurations

1. **Disable Debug Mode**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Application**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Set Proper Permissions**
   - Storage directory should be writable
   - All other directories should be read-only

4. **Use Environment Variables**
   - Never commit `.env` file
   - Use server environment variables for sensitive data

5. **Enable HTTPS**
   - Configure SSL certificate
   - Update `APP_URL` to use https://

## Additional Configuration

### Changing Application Port

```bash
php artisan serve --host=0.0.0.0 --port=8080
```

### Clearing Cache

```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear route cache
php artisan route:clear
```

### Reset Database

To start fresh (WARNING: This will delete all data):

```bash
php artisan migrate:fresh --seed
```

## Quick Setup Script (Windows PowerShell)

Save this as `setup.ps1`:

```powershell
# OMDB App Setup Script
Write-Host "Installing dependencies..." -ForegroundColor Green
composer install

Write-Host "Creating environment file..." -ForegroundColor Green
Copy-Item .env.example .env

Write-Host "Generating application key..." -ForegroundColor Green
php artisan key:generate

Write-Host "Please configure your .env file with database and API credentials" -ForegroundColor Yellow
Read-Host "Press Enter when ready to continue"

Write-Host "Running migrations..." -ForegroundColor Green
php artisan migrate

Write-Host "Seeding database..." -ForegroundColor Green
php artisan db:seed --class=UserSeeder

Write-Host "Setup complete! Starting server..." -ForegroundColor Green
php artisan serve
```

Run with:
```powershell
.\setup.ps1
```

## Quick Setup Script (Linux/Mac Bash)

Save this as `setup.sh`:

```bash
#!/bin/bash

echo "Installing dependencies..."
composer install

echo "Creating environment file..."
cp .env.example .env

echo "Generating application key..."
php artisan key:generate

echo "Please configure your .env file with database and API credentials"
read -p "Press Enter when ready to continue"

echo "Running migrations..."
php artisan migrate

echo "Seeding database..."
php artisan db:seed --class=UserSeeder

echo "Setup complete! Starting server..."
php artisan serve
```

Make executable and run:
```bash
chmod +x setup.sh
./setup.sh
```

## Getting Help

If you encounter issues not covered in this guide:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Review Laravel documentation: [https://laravel.com/docs/5.8](https://laravel.com/docs/5.8)
3. Check OMDB API documentation: [http://www.omdbapi.com/](http://www.omdbapi.com/)

## Next Steps

After successful installation:

1. Explore the movie search functionality
2. Test adding movies to favorites
3. Try switching between English and Indonesian languages
4. Test infinite scroll by scrolling through movie results
5. Observe lazy loading of movie posters

Enjoy using the OMDB Movie App!
