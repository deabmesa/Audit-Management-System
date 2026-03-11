#!/usr/bin/env bash
set -euo pipefail

if ! command -v php >/dev/null 2>&1; then
  echo "ERROR: php command not found"
  echo "Run: ./scripts/dev/install-php.sh"
  exit 1
fi

if [ ! -f artisan ]; then
  echo "ERROR: artisan file is missing"
  exit 1
fi

php artisan serve "$@"
