#!/bin/bash
set -e

echo "Starting Laravel deployment..."

if [ ! -f artisan ]; then
  echo "Error: artisan not found. Ensure you are in project root."
  exit 1
fi

if [ ! -f .env ]; then
  cp .env.example .env
  echo ".env created from .env.example"
fi

required_composer_files=(
  "vendor/autoload.php"
  "vendor/composer/autoload_classmap.php"
  "vendor/composer/autoload_namespaces.php"
  "vendor/composer/autoload_psr4.php"
  "vendor/composer/autoload_real.php"
  "vendor/composer/autoload_static.php"
  "vendor/composer/ClassLoader.php"
  "vendor/composer/installed.json"
  "vendor/composer/installed.php"
)

for file in "${required_composer_files[@]}"; do
  if [ ! -f "$file" ]; then
    echo "Error: Composer dependencies are incomplete ($file missing)."
    echo "Build vendor on an online machine and copy the complete vendor directory here for offline deployment."
    exit 1
  fi
done

if grep -q "Placeholder only" vendor/composer/installed.json 2>/dev/null; then
  echo "Error: Placeholder vendor files detected. Replace with a real Composer-built vendor directory."
  exit 1
fi

php artisan key:generate --force

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views
chmod -R 775 storage
chmod -R 775 bootstrap/cache

php artisan storage:link || true

php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

php artisan optimize
php artisan config:cache
php artisan route:cache

php artisan migrate --force
php artisan db:seed --force

echo "Deployment complete"
php artisan serve --host=0.0.0.0 --port=8000
