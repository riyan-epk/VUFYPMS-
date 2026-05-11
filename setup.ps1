# VUFYPMS Setup Script for Windows
# Run this in PowerShell as Administrator

Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "  VUFYPMS - Setup Script (Windows)" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host ""

# Check PHP
Write-Host "[1/7] Checking PHP..." -ForegroundColor Yellow
$phpVersion = php -v 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: PHP not found! Make sure XAMPP is installed and PHP is in PATH." -ForegroundColor Red
    Write-Host "Add C:\xampp\php to your System PATH variable." -ForegroundColor Red
    exit 1
}
Write-Host "PHP found: $($phpVersion -split '\n' | Select-Object -First 1)" -ForegroundColor Green

# Check Composer
Write-Host "[2/7] Checking Composer..." -ForegroundColor Yellow
$composerVersion = composer --version 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Composer not found! Download from https://getcomposer.org/" -ForegroundColor Red
    exit 1
}
Write-Host "Composer found: $composerVersion" -ForegroundColor Green

# Navigate to vufypms directory
$projectDir = Join-Path $PSScriptRoot "vufypms"
if (-not (Test-Path $projectDir)) {
    Write-Host "ERROR: vufypms/ directory not found!" -ForegroundColor Red
    exit 1
}

Set-Location $projectDir

# Install Composer dependencies
Write-Host "[3/7] Installing PHP dependencies (composer install)..." -ForegroundColor Yellow
composer install --no-interaction --prefer-dist
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Composer install failed!" -ForegroundColor Red
    exit 1
}
Write-Host "Dependencies installed successfully." -ForegroundColor Green

# Setup .env file
Write-Host "[4/7] Setting up .env file..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host ".env file created from .env.example" -ForegroundColor Green
} else {
    Write-Host ".env file already exists, skipping." -ForegroundColor Yellow
}

# Generate app key
Write-Host "[5/7] Generating application key..." -ForegroundColor Yellow
php artisan key:generate
Write-Host "Application key generated." -ForegroundColor Green

# Run migrations
Write-Host "[6/7] Running database migrations and seeders..." -ForegroundColor Yellow
Write-Host ""
Write-Host "IMPORTANT: Make sure:" -ForegroundColor Red
Write-Host "  1. XAMPP MySQL is running" -ForegroundColor Red
Write-Host "  2. Database 'vufypms' exists in phpMyAdmin" -ForegroundColor Red
Write-Host "  3. .env DB credentials are correct" -ForegroundColor Red
Write-Host ""

$confirm = Read-Host "Have you created the 'vufypms' database? (y/n)"
if ($confirm -eq 'y' -or $confirm -eq 'Y') {
    php artisan migrate --seed
    if ($LASTEXITCODE -ne 0) {
        Write-Host "ERROR: Migration failed! Check your .env database settings." -ForegroundColor Red
        exit 1
    }
    Write-Host "Migrations and seeders completed." -ForegroundColor Green
} else {
    Write-Host "Skipping migrations. Run manually: php artisan migrate --seed" -ForegroundColor Yellow
}

# Create storage link
Write-Host "[7/7] Creating storage symlink..." -ForegroundColor Yellow
php artisan storage:link
Write-Host "Storage link created." -ForegroundColor Green

# Create required storage directories
$storageDirs = @(
    "storage\app\public\documents",
    "storage\app\public\profiles",
    "storage\framework\cache\data",
    "storage\framework\sessions",
    "storage\framework\views",
    "storage\logs"
)
foreach ($dir in $storageDirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
}
Write-Host "Storage directories created." -ForegroundColor Green

Write-Host ""
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "  SETUP COMPLETE!" -ForegroundColor Green
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Start the development server:" -ForegroundColor White
Write-Host "  cd vufypms" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor Yellow
Write-Host ""
Write-Host "Then open: http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "Default Credentials:" -ForegroundColor White
Write-Host "  Admin:      admin@vu.edu.pk / password" -ForegroundColor Cyan
Write-Host "  Supervisor: supervisor@vu.edu.pk / password" -ForegroundColor Cyan
Write-Host "  Student:    student@vu.edu.pk / password" -ForegroundColor Cyan
Write-Host ""
