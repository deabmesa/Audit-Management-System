#!/usr/bin/env bash
set -euo pipefail

BUNDLE_DIR=${1:-offline-bundle}
mkdir -p "$BUNDLE_DIR"

echo "[1/4] Exporting source bundle"
git archive --format=tar.gz -o "$BUNDLE_DIR/source.tar.gz" HEAD

echo "[2/4] Caching composer dependencies (requires internet on build host)"
composer install --no-dev --prefer-dist --optimize-autoloader

echo "[3/4] Packing vendor directory"
tar -czf "$BUNDLE_DIR/vendor.tar.gz" vendor

echo "[4/4] Exporting composer cache"
COMPOSER_CACHE_DIR=$(composer config cache-dir)
tar -czf "$BUNDLE_DIR/composer-cache.tar.gz" -C "$COMPOSER_CACHE_DIR" .

echo "Offline bundle prepared in $BUNDLE_DIR"
