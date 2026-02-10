# Cleanup Script - Remove Old Laravel Files from Root
# Run this ONLY after confirming backend/ has all necessary files

Write-Host "🧹 DealMindanao Monorepo Cleanup Script" -ForegroundColor Cyan
Write-Host ""
Write-Host "This script will remove old Laravel files from the root directory" -ForegroundColor Yellow
Write-Host "that have been moved to the backend/ folder." -ForegroundColor Yellow
Write-Host ""
Write-Host "Files/folders to be removed:" -ForegroundColor Red
Write-Host "  - app/" -ForegroundColor Red
Write-Host "  - bootstrap/" -ForegroundColor Red
Write-Host "  - database/" -ForegroundColor Red
Write-Host "  - resources/" -ForegroundColor Red
Write-Host "  - storage/" -ForegroundColor Red
Write-Host "  - tests/" -ForegroundColor Red
Write-Host "  - vendor/" -ForegroundColor Red
Write-Host "  - node_modules/ (old)" -ForegroundColor Red
Write-Host "  - package.json (old)" -ForegroundColor Red
Write-Host "  - package-lock.json (old)" -ForegroundColor Red
Write-Host "  - vite.config.js (old)" -ForegroundColor Red
Write-Host "  - tailwind.config.js (old)" -ForegroundColor Red
Write-Host "  - postcss.config.js (old)" -ForegroundColor Red
Write-Host ""

$confirmation = Read-Host "Are you sure you want to proceed? (yes/no)"

if ($confirmation -ne "yes") {
    Write-Host "Cleanup cancelled." -ForegroundColor Yellow
    exit
}

Write-Host ""
Write-Host "Starting cleanup..." -ForegroundColor Green

# Remove Laravel directories
$laravelDirs = @("app", "bootstrap", "database", "resources", "storage", "tests", "vendor")
foreach ($dir in $laravelDirs) {
    if (Test-Path $dir) {
        Write-Host "Removing $dir/..." -ForegroundColor Yellow
        Remove-Item -Path $dir -Recurse -Force
    }
}

# Remove old frontend files
$frontendFiles = @("node_modules", "package.json", "package-lock.json", "vite.config.js", "tailwind.config.js", "postcss.config.js")
foreach ($file in $frontendFiles) {
    if (Test-Path $file) {
        Write-Host "Removing $file..." -ForegroundColor Yellow
        Remove-Item -Path $file -Recurse -Force
    }
}

Write-Host ""
Write-Host "✅ Cleanup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "Your monorepo structure is now clean:" -ForegroundColor Cyan
Write-Host "  📁 backend/  - Laravel API" -ForegroundColor Green
Write-Host "  📁 frontend/ - HTML + Tailwind + Vite" -ForegroundColor Green
Write-Host "  📄 docker-compose.yml" -ForegroundColor Green
Write-Host "  📄 SETUP_GUIDE.md" -ForegroundColor Green
Write-Host ""
