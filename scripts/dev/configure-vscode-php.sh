#!/usr/bin/env bash
set -euo pipefail

if ! command -v php >/dev/null 2>&1; then
  echo "php not found; attempting install"
  bash scripts/dev/install-php.sh
fi

PHP_PATH=$(command -v php)
mkdir -p .vscode

cat > .vscode/settings.json <<JSON
{
  "php.validate.enable": true,
  "php.validate.executablePath": "${PHP_PATH}",
  "intelephense.environment.phpVersion": "8.2.0"
}
JSON

echo "Configured VS Code PHP validation executable: ${PHP_PATH}"
