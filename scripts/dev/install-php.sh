#!/usr/bin/env bash
set -euo pipefail

if command -v php >/dev/null 2>&1; then
  php -v | head -n1
  echo "PHP is already installed."
  exit 0
fi

if command -v apt-get >/dev/null 2>&1; then
  sudo apt-get update
  sudo apt-get install -y php php-cli php-mbstring php-xml php-bcmath php-pgsql unzip
elif command -v dnf >/dev/null 2>&1; then
  sudo dnf module reset php -y
  sudo dnf module enable php:8.2 -y
  sudo dnf install -y php php-cli php-fpm php-mbstring php-xml php-bcmath php-pgsql unzip
else
  echo "Unsupported package manager. Install PHP 8.2+ manually and re-run."
  exit 1
fi

php -v | head -n1
