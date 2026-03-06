#!/usr/bin/env bash
set -euo pipefail

BUNDLE_DIR=${1:-offline-bundle}
APP_DIR=${2:-/var/www/audit-management-system}

mkdir -p "$APP_DIR"

echo "[1/5] Extracting source"
tar -xzf "$BUNDLE_DIR/source.tar.gz" -C "$APP_DIR"

echo "[2/5] Restoring vendor"
tar -xzf "$BUNDLE_DIR/vendor.tar.gz" -C "$APP_DIR"

cd "$APP_DIR"

if [ ! -f .env ]; then
  echo "[3/5] Creating .env from .env.rhel8"
  cp .env.rhel8 .env
fi

echo "[4/5] Laravel optimization"
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[5/5] Done"
echo "Offline install completed at $APP_DIR"
