#!/bin/bash
set -e

echo "Starting Laravel deployment..."

if [ ! -f .env ]; then
  cp .env.example .env
  echo ".env created from .env.example"
fi

php artisan key:generate --force

chmod -R 775 storage
chmod -R 775 bootstrap/cache

php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan optimize

php artisan migrate --force
php artisan db:seed --force

echo "Deployment complete"
php artisan serve --host=0.0.0.0 --port=8000
