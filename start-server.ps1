# OMDB Movie App - Start Server Script
# This script starts the Laravel development server using PHP 7.4

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   OMDB Movie App - Starting Server    " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env file exists
if (!(Test-Path ".env")) {
    Write-Host "Error: .env file not found!" -ForegroundColor Red
    Write-Host "Please copy .env.example to .env and configure it." -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit 1
}

# Check PHP
Write-Host "Checking PHP installation..." -ForegroundColor Yellow
try {
    $phpVersion = & C:\php74\php.exe -v 2>&1 | Select-Object -First 1
    Write-Host "✓ $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ PHP 7.4 not found at C:\php74\php.exe" -ForegroundColor Red
    Write-Host "  Please install PHP 7.4 or update the path in this script." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Server Information:" -ForegroundColor White
Write-Host "  URL: http://localhost:8000" -ForegroundColor Green
Write-Host "  Credentials:" -ForegroundColor White
Write-Host "    Username: aldmic" -ForegroundColor Yellow
Write-Host "    Password: 123abc123" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Starting Laravel development server..." -ForegroundColor Yellow
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Gray
Write-Host ""

# Start the server
C:\php74\php.exe artisan serve --host=127.0.0.1 --port=8000
