# Deployment Guide (Red Hat Enterprise Linux 8)

## 1) OS Packages

```bash
sudo dnf install -y epel-release
sudo dnf module reset php -y
sudo dnf module enable php:8.2 -y
sudo dnf install -y nginx php php-cli php-fpm php-pgsql php-mbstring php-xml php-json php-bcmath php-zip unzip git redis
```

## 2) Composer

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

## 3) Database Prerequisites

- Execute main PostgreSQL setup SQL (`sql/postgresql/01_main_database_setup.sql`)
- Execute external PostgreSQL read-only setup SQL (`sql/postgresql/02_external_staff_readonly_setup.sql`)
- Configure Oracle read-only user (see `docs/operations/oracle-readonly-setup.md`)

## 4) Online Deployment

```bash
cd /var/www
sudo git clone <repo-url> audit-management-system
cd audit-management-system
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.rhel8 .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 5) Offline (Air-Gapped) Deployment

On connected build host:

```bash
./scripts/rhel8/prepare-offline-bundle.sh offline-bundle
```

On offline target host:

```bash
./scripts/rhel8/install-offline.sh offline-bundle /var/www/audit-management-system
```

## 6) PHP-FPM

Edit `/etc/php-fpm.d/www.conf` and ensure:

- `user = nginx`
- `group = nginx`
- `listen = /run/php-fpm/www.sock`

```bash
sudo systemctl enable --now php-fpm
```

## 7) Nginx

`/etc/nginx/conf.d/audit.conf`:

```nginx
server {
    listen 80;
    server_name audit.example.com;
    root /var/www/audit-management-system/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php-fpm/www.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

```bash
sudo nginx -t
sudo systemctl enable --now nginx
```

## 8) Redis

```bash
sudo systemctl enable --now redis
```

In `.env`:

- `CACHE_DRIVER=redis`
- `SESSION_DRIVER=redis`
- `QUEUE_CONNECTION=redis`

## 9) Security Hardening Checklist

- External DB users must be SELECT-only
- Enforce TLS to DB endpoints
- Enable HTTPS and enterprise certs
- Rotate logs and back up DBs


## 10) Troubleshooting: `OPENSSL_1_1_1` not found

If you see an error like:

```text
php: /lib/x86_64-linux-gnu/libcrypto.so.1.1: version `OPENSSL_1_1_1' not found (required by php)
```

Run preflight checks:

```bash
./scripts/rhel8/php-preflight.sh
```

Typical fixes:

1. Ensure PHP and OpenSSL come from the same OS/repo build chain.
2. Remove/adjust `LD_LIBRARY_PATH` so PHP does not load incompatible OpenSSL libraries first.
3. If Oracle client variables are set globally, keep them scoped to Oracle-only tooling and not PHP-FPM/Nginx processes.
4. Restart services after environment changes:

```bash
sudo systemctl daemon-reload
sudo systemctl restart php-fpm nginx
```

For PHP-FPM, verify no conflicting env vars in `/etc/systemd/system/php-fpm.service.d/*.conf` or pool configs.

See also: `docs/operations/php-openssl-troubleshooting.md`.
