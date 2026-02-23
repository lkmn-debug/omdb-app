#!/bin/bash

# OMDB App Quick Setup Script
# Run this script after configuring your .env file

echo "====================================="
echo "  OMDB Movie App - Setup Script"
echo "====================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Check if composer is installed
echo -e "${YELLOW}[1/7] Checking Composer...${NC}"
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓ Composer found: $(composer --version)${NC}"
else
    echo -e "${RED}✗ Composer not found. Please install Composer first.${NC}"
    exit 1
fi

# Check if PHP is installed
echo -e "${YELLOW}[2/7] Checking PHP...${NC}"
if command -v php &> /dev/null; then
    echo -e "${GREEN}✓ PHP found: $(php -v | head -n 1)${NC}"
else
    echo -e "${RED}✗ PHP not found. Please install PHP first.${NC}"
    exit 1
fi

# Install dependencies
echo -e "${YELLOW}[3/7] Installing Composer dependencies...${NC}"
composer install --no-interaction
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Dependencies installed successfully${NC}"
else
    echo -e "${RED}✗ Failed to install dependencies${NC}"
    exit 1
fi

# Copy .env file if it doesn't exist
echo -e "${YELLOW}[4/7] Setting up environment file...${NC}"
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ Environment file created${NC}"
else
    echo -e "${GREEN}✓ Environment file already exists${NC}"
fi

# Generate application key
echo -e "${YELLOW}[5/7] Generating application key...${NC}"
php artisan key:generate --no-interaction
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Application key generated${NC}"
else
    echo -e "${RED}✗ Failed to generate application key${NC}"
    exit 1
fi

# Prompt for database configuration
echo ""
echo -e "${CYAN}=====================================${NC}"
echo -e "${CYAN}  Database Configuration${NC}"
echo -e "${CYAN}=====================================${NC}"
echo -e "${YELLOW}Please ensure you have:${NC}"
echo "1. Created a database named 'omdb_app'"
echo "2. Updated .env file with your database credentials"
echo "3. Added your OMDB API key to .env file"
echo ""
echo -e "${YELLOW}Get your free OMDB API key from: http://www.omdbapi.com/apikey.aspx${NC}"
echo ""
read -p "Have you configured the .env file? (Y/N): " continue

if [ "$continue" != "Y" ] && [ "$continue" != "y" ]; then
    echo ""
    echo -e "${YELLOW}Please configure your .env file and run this script again.${NC}"
    echo ""
    echo "Required configurations:"
    echo "- DB_DATABASE=omdb_app"
    echo "- DB_USERNAME=your_username"
    echo "- DB_PASSWORD=your_password"
    echo "- OMDB_API_KEY=your_api_key"
    exit 0
fi

# Set proper permissions
echo -e "${YELLOW}[6/7] Setting proper permissions...${NC}"
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"

# Run migrations
echo -e "${YELLOW}[7/7] Running database migrations...${NC}"
php artisan migrate --no-interaction
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database tables created successfully${NC}"
else
    echo -e "${RED}✗ Failed to run migrations. Please check your database configuration.${NC}"
    exit 1
fi

# Seed database
echo -e "${YELLOW}[8/8] Seeding database with test user...${NC}"
php artisan db:seed --class=UserSeeder --no-interaction
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Test user created successfully${NC}"
else
    echo -e "${RED}✗ Failed to seed database${NC}"
    exit 1
fi

# Setup complete
echo ""
echo -e "${GREEN}=====================================${NC}"
echo -e "${GREEN}  Setup Complete!${NC}"
echo -e "${GREEN}=====================================${NC}"
echo ""
echo -e "${CYAN}Test User Credentials:${NC}"
echo "  Username: aldmic"
echo "  Password: 123abc123"
echo ""
echo -e "${YELLOW}To start the development server, run:${NC}"
echo "  php artisan serve"
echo ""
echo -e "${YELLOW}Then open your browser and navigate to:${NC}"
echo "  http://localhost:8000"
echo ""

read -p "Would you like to start the server now? (Y/N): " startServer
if [ "$startServer" == "Y" ] || [ "$startServer" == "y" ]; then
    echo ""
    echo -e "${GREEN}Starting development server...${NC}"
    echo -e "${YELLOW}Press Ctrl+C to stop the server${NC}"
    echo ""
    php artisan serve
fi
