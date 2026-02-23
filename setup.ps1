# OMDB App Quick Setup Script
# Run this script after configuring your .env file

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  OMDB Movie App - Setup Script" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Check if composer is installed
Write-Host "[1/7] Checking Composer..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version 2>&1
    Write-Host "✓ Composer found: $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Composer not found. Please install Composer first." -ForegroundColor Red
    exit 1
}

# Check if PHP is installed
Write-Host "[2/7] Checking PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php -v 2>&1 | Select-Object -First 1
    Write-Host "✓ PHP found: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ PHP not found. Please install PHP first." -ForegroundColor Red
    exit 1
}

# Install dependencies
Write-Host "[3/7] Installing Composer dependencies..." -ForegroundColor Yellow
composer install --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Dependencies installed successfully" -ForegroundColor Green
} else {
    Write-Host "✗ Failed to install dependencies" -ForegroundColor Red
    exit 1
}

# Copy .env file if it doesn't exist
Write-Host "[4/7] Setting up environment file..." -ForegroundColor Yellow
if (!(Test-Path .env)) {
    Copy-Item .env.example .env
    Write-Host "✓ Environment file created" -ForegroundColor Green
} else {
    Write-Host "✓ Environment file already exists" -ForegroundColor Green
}

# Generate application key
Write-Host "[5/7] Generating application key..." -ForegroundColor Yellow
php artisan key:generate --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Application key generated" -ForegroundColor Green
} else {
    Write-Host "✗ Failed to generate application key" -ForegroundColor Red
    exit 1
}

# Prompt for database configuration
Write-Host ""
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Database Configuration" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "Please ensure you have:" -ForegroundColor Yellow
Write-Host "1. Created a database named 'omdb_app'" -ForegroundColor White
Write-Host "2. Updated .env file with your database credentials" -ForegroundColor White
Write-Host "3. Added your OMDB API key to .env file" -ForegroundColor White
Write-Host ""
Write-Host "Get your free OMDB API key from: http://www.omdbapi.com/apikey.aspx" -ForegroundColor Yellow
Write-Host ""
$continue = Read-Host "Have you configured the .env file? (Y/N)"

if ($continue -ne "Y" -and $continue -ne "y") {
    Write-Host ""
    Write-Host "Please configure your .env file and run this script again." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Required configurations:" -ForegroundColor White
    Write-Host "- DB_DATABASE=omdb_app" -ForegroundColor White
    Write-Host "- DB_USERNAME=your_username" -ForegroundColor White
    Write-Host "- DB_PASSWORD=your_password" -ForegroundColor White
    Write-Host "- OMDB_API_KEY=your_api_key" -ForegroundColor White
    exit 0
}

# Run migrations
Write-Host "[6/7] Running database migrations..." -ForegroundColor Yellow
php artisan migrate --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Database tables created successfully" -ForegroundColor Green
} else {
    Write-Host "✗ Failed to run migrations. Please check your database configuration." -ForegroundColor Red
    exit 1
}

# Seed database
Write-Host "[7/7] Seeding database with test user..." -ForegroundColor Yellow
php artisan db:seed --class=UserSeeder --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Test user created successfully" -ForegroundColor Green
} else {
    Write-Host "✗ Failed to seed database" -ForegroundColor Red
    exit 1
}

# Setup complete
Write-Host ""
Write-Host "=====================================" -ForegroundColor Green
Write-Host "  Setup Complete!" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host ""
Write-Host "Test User Credentials:" -ForegroundColor Cyan
Write-Host "  Username: aldmic" -ForegroundColor White
Write-Host "  Password: 123abc123" -ForegroundColor White
Write-Host ""
Write-Host "To start the development server, run:" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Then open your browser and navigate to:" -ForegroundColor Yellow
Write-Host "  http://localhost:8000" -ForegroundColor White
Write-Host ""

$startServer = Read-Host "Would you like to start the server now? (Y/N)"
if ($startServer -eq "Y" -or $startServer -eq "y") {
    Write-Host ""
    Write-Host "Starting development server..." -ForegroundColor Green
    Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
    Write-Host ""
    php artisan serve
}
