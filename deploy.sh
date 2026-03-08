#!/usr/bin/env bash
# =============================================================================
# DealMindanao — Laravel Backend Deployment Script
# =============================================================================
# Run this script on your production server after pulling the latest code.
#
# USAGE:
#   chmod +x deploy.sh
#   ./deploy.sh
#
# PREREQUISITES:
#   - PHP 8.2+, Composer installed on server
#   - .env already configured from backend/.env.production.example
#   - APP_ENV=production in .env
#   - Storage symlink created at least once (handled below)
# =============================================================================

set -e  # Exit immediately on any error

# Colours
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'

info()    { echo -e "${BLUE}[INFO]${NC} $1"; }
success() { echo -e "${GREEN}[OK]${NC} $1"; }
warn()    { echo -e "${YELLOW}[WARN]${NC} $1"; }
error()   { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

echo ""
echo -e "${BLUE}============================================${NC}"
echo -e "${BLUE}   DealMindanao — Laravel Deployment        ${NC}"
echo -e "${BLUE}============================================${NC}"
echo ""

# ---------------------------------------------------------------------------
# 0. Safety checks
# ---------------------------------------------------------------------------
info "Checking environment..."

if [ ! -f "backend/.env" ]; then
    error "backend/.env not found. Copy .env.production.example and fill it in first."
fi

APP_ENV_VALUE=$(grep -E "^APP_ENV=" backend/.env | cut -d'=' -f2 | tr -d '"')
if [ "$APP_ENV_VALUE" != "production" ]; then
    error "APP_ENV is '${APP_ENV_VALUE}' — must be 'production' before deploying."
fi

APP_DEBUG_VALUE=$(grep -E "^APP_DEBUG=" backend/.env | cut -d'=' -f2 | tr -d '"')
if [ "$APP_DEBUG_VALUE" = "true" ]; then
    error "APP_DEBUG=true — set it to false before deploying."
fi

success "Environment checks passed (APP_ENV=production, APP_DEBUG=false)"

# ---------------------------------------------------------------------------
# 1. Pull latest code
# ---------------------------------------------------------------------------
info "Pulling latest code from git..."
git pull origin master
success "Code updated"

# ---------------------------------------------------------------------------
# 2. PHP dependencies (no dev packages, autoloader optimized)
# ---------------------------------------------------------------------------
info "Installing PHP dependencies..."
cd backend
composer install --no-dev --optimize-autoloader --no-interaction
success "Composer done"

# ---------------------------------------------------------------------------
# 3. Run database migrations (SAFE — never drops existing data)
# ---------------------------------------------------------------------------
info "Running migrations..."
php artisan migrate --force
success "Migrations done"

# ---------------------------------------------------------------------------
# 4. Create sessions table if using database driver
# ---------------------------------------------------------------------------
SESSION_DRIVER=$(grep -E "^SESSION_DRIVER=" .env | cut -d'=' -f2 | tr -d '"')
if [ "$SESSION_DRIVER" = "database" ]; then
    info "Creating sessions table (if not exists)..."
    php artisan session:table 2>/dev/null || true
    php artisan migrate --force 2>/dev/null || true
    success "Sessions table ready"
fi

# ---------------------------------------------------------------------------
# 5. Storage symlink
# ---------------------------------------------------------------------------
info "Ensuring storage symlink exists..."
php artisan storage:link 2>/dev/null || warn "Symlink already exists or skipped"
success "Storage symlink OK"

# ---------------------------------------------------------------------------
# 6. Clear and rebuild all caches (big performance boost)
# ---------------------------------------------------------------------------
info "Rebuilding production caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
success "Caches rebuilt"

# ---------------------------------------------------------------------------
# 7. Set correct file permissions
# ---------------------------------------------------------------------------
info "Setting file permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || \
    warn "Could not chown (run as root if needed): chown -R www-data:www-data storage bootstrap/cache"
success "Permissions set"

cd ..

# ---------------------------------------------------------------------------
# Reminders
# ---------------------------------------------------------------------------
echo ""
warn "REMINDER: Make sure the queue worker is running via Supervisor:"
echo -e "  ${YELLOW}php artisan queue:work --sleep=3 --tries=3 --max-time=3600${NC}"
echo ""
warn "REMINDER: Add dealmindanao.com to your reCAPTCHA allowed domains:"
echo -e "  ${YELLOW}https://www.google.com/recaptcha/admin${NC}"
echo ""

# ---------------------------------------------------------------------------
# Done
# ---------------------------------------------------------------------------
echo -e "${GREEN}============================================${NC}"
echo -e "${GREEN}   Deployment complete!                    ${NC}"
echo -e "${GREEN}============================================${NC}"
echo ""
