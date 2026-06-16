#!/bin/bash
set -euo pipefail

# HERO Reservation System — Hostinger deploy script
# Run on server: bash scripts/hostinger-deploy.sh

APP_URL="https://snow-emu-551412.hostingersite.com"
REPO="https://github.com/matlobalam640/ReservationSystem.git"
DOMAIN_DIR="$HOME/domains/snow-emu-551412.hostingersite.com/public_html"

echo "==> ReservationSystem deploy"
echo "    Target: $DOMAIN_DIR"
echo "    URL:    $APP_URL"

mkdir -p "$(dirname "$DOMAIN_DIR")"

if [ ! -f "$DOMAIN_DIR/artisan" ]; then
  echo "==> Cloning repository..."
  if [ -d "$DOMAIN_DIR" ] && [ "$(ls -A "$DOMAIN_DIR" 2>/dev/null | head -1)" != "" ]; then
    echo "    public_html is not empty — moving aside to public_html.bak.$(date +%s)"
    mv "$DOMAIN_DIR" "${DOMAIN_DIR}.bak.$(date +%s)"
  fi
  git clone --branch main "$REPO" "$DOMAIN_DIR"
fi

cd "$DOMAIN_DIR"

echo "==> Pulling latest..."
git pull origin main

if [ ! -f .env ]; then
  echo "==> Creating .env from .env.example"
  cp .env.example .env
fi

# Ensure production URL (safe to re-run)
grep -q '^APP_URL=' .env && sed -i "s|^APP_URL=.*|APP_URL=$APP_URL|" .env || echo "APP_URL=$APP_URL" >> .env
grep -q '^APP_ENV=' .env && sed -i 's|^APP_ENV=.*|APP_ENV=production|' .env || echo 'APP_ENV=production' >> .env
grep -q '^APP_DEBUG=' .env && sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' .env || echo 'APP_DEBUG=false' >> .env
grep -q '^DB_CONNECTION=' .env && sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=mysql|' .env || echo 'DB_CONNECTION=mysql' >> .env
grep -q '^SESSION_DRIVER=' .env && sed -i 's|^SESSION_DRIVER=.*|SESSION_DRIVER=database|' .env || echo 'SESSION_DRIVER=database' >> .env
grep -q '^QUEUE_CONNECTION=' .env && sed -i 's|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=database|' .env || echo 'QUEUE_CONNECTION=database' >> .env
grep -q '^CACHE_STORE=' .env && sed -i 's|^CACHE_STORE=.*|CACHE_STORE=database|' .env || echo 'CACHE_STORE=database' >> .env

if ! grep -q '^APP_KEY=base64:' .env; then
  echo "==> Generating APP_KEY"
  php artisan key:generate --force
fi

if grep -q '^DB_DATABASE=$' .env || grep -q '^DB_DATABASE=laravel$' .env || grep -q '^DB_CONNECTION=sqlite' .env; then
  echo ""
  echo "!! Edit .env with Hostinger MySQL credentials before continuing:"
  echo "   DB_HOST=localhost"
  echo "   DB_DATABASE=..."
  echo "   DB_USERNAME=..."
  echo "   DB_PASSWORD=..."
  echo ""
  echo "   nano .env"
  echo "   Then re-run: bash scripts/hostinger-deploy.sh"
  exit 1
fi

echo "==> Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding database (roles + sample data)..."
php artisan db:seed --force

echo "==> Storage link + caches..."
php artisan storage:link 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "==> Deploy complete."
echo "    Site:  $APP_URL"
echo "    Admin: $APP_URL/admin"
echo "    Login: $APP_URL/login"
echo ""
echo "!! Set document root in hPanel to:"
echo "    $DOMAIN_DIR/public"
echo ""
echo "Default admin (change after login): admin@hero.ops / password"
