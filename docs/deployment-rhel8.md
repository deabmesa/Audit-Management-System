# Deployment Guide (Red Hat Enterprise Linux 8)

## 1. Install Required Packages

```bash
sudo dnf install -y epel-release
sudo dnf module reset php -y
sudo dnf module enable php:8.2 -y
sudo dnf install -y nginx php php-cli php-fpm php-pgsql php-mbstring php-xml php-json php-bcmath php-zip unzip git redis
```

Install Composer:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

## 2. PostgreSQL + Oracle Client

- Install PostgreSQL client/server per enterprise standard.
- Install Oracle Instant Client (basic + sdk) for `yajra/laravel-oci8`.

## 3. Application Setup

```bash
cd /var/www
sudo git clone <repo-url> audit-management-system
cd audit-management-system
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 4. Configure PHP-FPM

Edit `/etc/php-fpm.d/www.conf`:

- `user = nginx`
- `group = nginx`
- `listen = /run/php-fpm/www.sock`

Then:

```bash
sudo systemctl enable --now php-fpm
```

## 5. Configure Nginx

Example virtual host (`/etc/nginx/conf.d/audit.conf`):

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

## 6. Redis Cache

```bash
sudo systemctl enable --now redis
```

Set `.env`:

- `CACHE_DRIVER=redis`
- `SESSION_DRIVER=redis`
- `QUEUE_CONNECTION=redis`

## 7. Security Hardening

- Restrict DB users for external DBs to **SELECT only**.
- Set strict firewall/security groups.
- Enforce HTTPS with enterprise certificates.
- Run scheduled backups and rotate logs.

## 8. Process Supervision

Use systemd or Supervisor for queue workers:

```bash
php artisan queue:work --sleep=3 --tries=3
```
