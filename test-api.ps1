# Test OMDB API Connection
# This script tests if your OMDB API key is working correctly

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   OMDB API Connection Test            " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env file exists
if (!(Test-Path ".env")) {
    Write-Host "Error: .env file not found!" -ForegroundColor Red
    Write-Host "Please copy .env.example to .env and configure it." -ForegroundColor Yellow
    exit 1
}

# Read API key from .env
$envContent = Get-Content ".env"
$apiKeyLine = $envContent | Where-Object { $_ -match "^OMDB_API_KEY=" }

if (!$apiKeyLine) {
    Write-Host "Error: OMDB_API_KEY not found in .env file!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please add this line to your .env file:" -ForegroundColor Yellow
    Write-Host "OMDB_API_KEY=your_api_key_here" -ForegroundColor White
    Write-Host ""
    Write-Host "Get your free API key at: http://www.omdbapi.com/" -ForegroundColor Cyan
    exit 1
}

$apiKey = $apiKeyLine -replace "OMDB_API_KEY=", "" -replace '"', ''

if ($apiKey -eq "" -or $apiKey -eq "your_api_key_here") {
    Write-Host "Error: OMDB_API_KEY is not configured!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Your current .env has:" -ForegroundColor Yellow
    Write-Host "  OMDB_API_KEY=$apiKey" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Please replace it with your actual API key." -ForegroundColor Yellow
    Write-Host "Get your free API key at: http://www.omdbapi.com/" -ForegroundColor Cyan
    exit 1
}

Write-Host "API Key found: " -NoNewline
Write-Host $apiKey.Substring(0, 4) -NoNewline -ForegroundColor Green
Write-Host "****" -ForegroundColor Gray
Write-Host ""

# Test API connection
Write-Host "Testing connection to OMDB API..." -ForegroundColor Yellow
$testUrl = "http://www.omdbapi.com/?apikey=$apiKey&s=movie&page=1"

try {
    $response = Invoke-WebRequest -Uri $testUrl -TimeoutSec 10 -UseBasicParsing
    $data = $response.Content | ConvertFrom-Json
    
    if ($data.Response -eq "True") {
        Write-Host "✓ Success! API connection is working." -ForegroundColor Green
        Write-Host ""
        Write-Host "Test Results:" -ForegroundColor White
        Write-Host "  Total Results: $($data.totalResults)" -ForegroundColor Cyan
        Write-Host "  Movies Found: $($data.Search.Count)" -ForegroundColor Cyan
        Write-Host ""
        Write-Host "Sample Movies:" -ForegroundColor White
        foreach ($movie in $data.Search | Select-Object -First 3) {
            Write-Host "  - $($movie.Title) ($($movie.Year))" -ForegroundColor Gray
        }
        Write-Host ""
        Write-Host "Your OMDB API is ready to use! ✓" -ForegroundColor Green
    } else {
        Write-Host "✗ API Error: $($data.Error)" -ForegroundColor Red
        Write-Host ""
        Write-Host "Common Issues:" -ForegroundColor Yellow
        Write-Host "  1. Invalid API key" -ForegroundColor Gray
        Write-Host "  2. API key not activated (check your email)" -ForegroundColor Gray
        Write-Host "  3. Daily limit reached (1,000 requests for free tier)" -ForegroundColor Gray
    }
} catch {
    Write-Host "✗ Connection Failed!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Possible causes:" -ForegroundColor White
    Write-Host "  1. No internet connection" -ForegroundColor Gray
    Write-Host "  2. Firewall blocking the connection" -ForegroundColor Gray
    Write-Host "  3. OMDB API is down (rare)" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Try accessing this URL in your browser:" -ForegroundColor White
    Write-Host "  http://www.omdbapi.com/?apikey=$apiKey&s=batman" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Read-Host "Press Enter to exit"
