#!/bin/bash
set -e

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

has_placeholder_vendor() {
  grep -q "Placeholder only" vendor/composer/installed.json 2>/dev/null \
    || grep -q "ships without full Composer dependencies" vendor/autoload.php 2>/dev/null
}

extract_vendor_archive() {
  local archive_path="${VENDOR_ARCHIVE:-}"

  if [ -z "$archive_path" ]; then
    for candidate in vendor.tar.gz vendor.tgz vendor.zip; do
      if [ -f "$candidate" ]; then
        archive_path="$candidate"
        break
      fi
    done
  fi

  if [ -z "$archive_path" ]; then
    return 1
  fi

  echo "Found packaged vendor archive: $archive_path"
  rm -rf vendor/
  mkdir -p vendor

  case "$archive_path" in
    *.tar.gz|*.tgz)
      tar -xzf "$archive_path"
      ;;
    *.zip)
      unzip -oq "$archive_path"
      ;;
    *)
      echo "Error: unsupported vendor archive format: $archive_path"
      return 1
      ;;
  esac
}

print_vendor_rebuild_help() {
  echo "Placeholder vendor files detected."
  echo "To prepare a real deployment package, build dependencies on a connected Linux machine that matches the target OS as closely as possible (for example Red Hat 8.10)."
  echo "Suggested commands:"
  echo "  rm -rf vendor/"
  echo "  composer install --no-dev --optimize-autoloader"
  echo "Optional offline packaging:"
  echo "  tar -czf vendor.tar.gz vendor/"
  echo "Then copy the generated vendor/ directory or vendor archive back into this project and rerun ./deploy.sh."
}

echo "Starting Laravel deployment..."

if [ ! -f artisan ]; then
  echo "Error: artisan not found. Ensure you are in project root."
  exit 1
fi

if [ ! -f .env ]; then
  cp .env.example .env
  echo ".env created from .env.example"
fi

for file in "${required_composer_files[@]}"; do
  if [ ! -f "$file" ]; then
    echo "Error: Composer dependencies are incomplete ($file missing)."
    echo "Build vendor on an online machine and copy the complete vendor directory here for offline deployment."
    exit 1
  fi
done

if has_placeholder_vendor; then
  if extract_vendor_archive; then
    echo "Packaged vendor archive extracted successfully."
  elif command -v composer >/dev/null 2>&1; then
    echo "Placeholder vendor files detected. Attempting to rebuild Composer autoload files locally..."
    rm -rf vendor/

    if composer install --no-dev --optimize-autoloader; then
      echo "Composer dependencies rebuilt successfully."
    else
      echo "Error: automatic Composer install failed."
      print_vendor_rebuild_help
      exit 1
    fi
  else
    echo "Error: Placeholder vendor files detected and Composer is not available on this server."
    echo "No packaged vendor archive (vendor.tar.gz, vendor.tgz, or vendor.zip) was found."
    print_vendor_rebuild_help
    exit 1
  fi
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
