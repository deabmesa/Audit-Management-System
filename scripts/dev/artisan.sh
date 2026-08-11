#!/usr/bin/env bash
set -euo pipefail

if ! command -v php >/dev/null 2>&1; then
  echo "php command not found. Installing PHP..."
  bash scripts/dev/install-php.sh
fi

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
  echo "Created .env from .env.example"
fi

php artisan "$@"
