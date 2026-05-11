# =============================================================================
# VUFYPMS — Windows Setup Script (PowerShell)
# Virtual University Final Year Project Management System
# =============================================================================
# Run: Right-click this file → "Run with PowerShell" (or: .\setup.ps1)
# =============================================================================

$projectPath = $PSScriptRoot
Set-Location $projectPath

Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "  VUFYPMS — Setup Script" -ForegroundColor Cyan
Write-Host "  Virtual University FYP Management System" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Check PHP
Write-Host "[1/7] Checking PHP..." -ForegroundColor Yellow
$phpVersion = php -r "echo PHP_VERSION;" 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: PHP not found. Please install PHP 8.1+ (XAMPP recommended)." -ForegroundColor Red
    Write-Host "Download XAMPP: https://www.apachefriends.org/download.html" -ForegroundColor Red
    exit 1
}
Write-Host "   PHP found: $phpVersion" -ForegroundColor Green

# Step 2: Check Composer
Write-Host "[2/7] Checking Composer..." -ForegroundColor Yellow
$composerVersion = composer --version 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Composer not found. Please install Composer." -ForegroundColor Red
    Write-Host "Download: https://getcomposer.org/download/" -ForegroundColor Red
    exit 1
}
Write-Host "   $composerVersion" -ForegroundColor Green

# Step 3: Install PHP dependencies
Write-Host "[3/7] Installing PHP dependencies (composer install)..." -ForegroundColor Yellow
composer install --no-interaction --prefer-dist --optimize-autoloader
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Composer install failed." -ForegroundColor Red
    exit 1
}
Write-Host "   Dependencies installed." -ForegroundColor Green

# Step 4: Setup .env
Write-Host "[4/7] Setting up environment file..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "   .env created from .env.example" -ForegroundColor Green
} else {
    Write-Host "   .env already exists." -ForegroundColor Green
}

# Generate app key
php artisan key:generate --ansi
Write-Host "   Application key generated." -ForegroundColor Green

# Step 5: Configure database
Write-Host "[5/7] Configuring database..." -ForegroundColor Yellow
Write-Host ""
Write-Host "   Please ensure:" -ForegroundColor Cyan
Write-Host "   1. XAMPP MySQL service is running" -ForegroundColor Cyan
Write-Host "   2. Database 'vufypms' exists in MySQL" -ForegroundColor Cyan
Write-Host "      (Create it: http://localhost/phpmyadmin)" -ForegroundColor Cyan
Write-Host ""

$confirm = Read-Host "   Have you created the 'vufypms' database? (yes/no)"
if ($confirm -ne "yes" -and $confirm -ne "y") {
    Write-Host "   Please create the database first, then re-run this script." -ForegroundColor Red
    exit 0
}

# Step 6: Run migrations and seed
Write-Host "[6/7] Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Migration failed. Check DB_HOST, DB_DATABASE, DB_USERNAME in .env" -ForegroundColor Red
    exit 1
}
Write-Host "   Migrations completed." -ForegroundColor Green

Write-Host "   Seeding database with demo data..." -ForegroundColor Yellow
php artisan db:seed --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "WARNING: Seeding failed." -ForegroundColor Red
} else {
    Write-Host "   Demo data seeded." -ForegroundColor Green
}

# Step 7: Storage link
Write-Host "[7/7] Creating storage symbolic link..." -ForegroundColor Yellow
php artisan storage:link
Write-Host "   Storage link created." -ForegroundColor Green

# Cache optimization
Write-Host ""
Write-Host "Optimizing application..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
Write-Host "   Optimization complete." -ForegroundColor Green

Write-Host ""
Write-Host "=============================================" -ForegroundColor Green
Write-Host "  SETUP COMPLETE!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host ""
Write-Host "  Default Login Credentials:" -ForegroundColor Cyan
Write-Host "  ──────────────────────────────────────────" -ForegroundColor Cyan
Write-Host "  ADMIN      : admin@vu.edu.pk / password" -ForegroundColor White
Write-Host "  SUPERVISOR : supervisor@vu.edu.pk / password" -ForegroundColor White
Write-Host "  STUDENT    : student@vu.edu.pk / password" -ForegroundColor White
Write-Host ""
Write-Host "  Start the development server:" -ForegroundColor Cyan
Write-Host "  php artisan serve" -ForegroundColor Yellow
Write-Host ""
Write-Host "  Then open: http://localhost:8000" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
