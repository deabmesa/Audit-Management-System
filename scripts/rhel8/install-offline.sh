#!/usr/bin/env bash
set -euo pipefail

BUNDLE_DIR=${1:-offline-bundle}
APP_DIR=${2:-/var/www/audit-management-system}

mkdir -p "$APP_DIR"

echo "[1/6] Extracting source"
tar -xzf "$BUNDLE_DIR/source.tar.gz" -C "$APP_DIR"

echo "[2/6] Restoring vendor"
tar -xzf "$BUNDLE_DIR/vendor.tar.gz" -C "$APP_DIR"

cd "$APP_DIR"

if [ ! -f .env ]; then
  echo "[3/6] Creating .env from .env.rhel8"
  cp .env.rhel8 .env
fi

echo "[4/6] Running PHP preflight"
./scripts/rhel8/php-preflight.sh

if [ "${APP_SKIP_ARTISAN:-0}" = "1" ]; then
  echo "[5/6] Skipping artisan bootstrap (APP_SKIP_ARTISAN=1)"
else
  echo "[5/6] Laravel optimization"
  php artisan key:generate --force
  php artisan migrate --force
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

echo "[6/6] Done"
echo "Offline install completed at $APP_DIR"
