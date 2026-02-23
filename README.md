# Enterprise Audit Management System (Laravel)

Production-ready Audit Management System architecture targeting **RHEL 8**, **PHP 8.x**, **PostgreSQL (RW)**, and **Oracle (RO)**.

## Core Features
- Laravel MVC with Blade + Bootstrap UI.
- Service-layer oriented controllers.
- RBAC roles: Admin, Auditor, Manager, Viewer.
- Attendance auto check-in and manual check-out.
- Staff Information module (CRUD-ready foundation).
- PAMS module (findings, recommendations, audit workflow foundation).
- Oracle reporting engine with strict read-only query usage.

## Data Model (PostgreSQL)
Migrations included for:
- users
- roles
- role_user
- staff
- audits
- audit_assignments
- attendance_logs
- audit_plans
- workpapers
- findings
- recommendations
- attachments

## Multi-Database Setup
`config/database.php` defines:
- `pgsql`: primary read/write application database.
- `oracle`: secondary reporting database via OCI8 driver.

> Oracle usage is intentionally implemented through reporting service classes with `DB::connection('oracle')` and select queries only.

## Authentication & Security
- Designed for Laravel Breeze integration (`composer require laravel/breeze --dev && php artisan breeze:install`).
- CSRF protection and session auth middleware.
- Role middleware (`role`) and route-level authorization.
- Attendance middleware to prevent multiple active sessions.

## Local Setup
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## RHEL 8 Deployment Guide

### 1) Install required packages
```bash
sudo dnf update -y
sudo dnf install -y epel-release
sudo dnf install -y php php-cli php-fpm php-pgsql php-mbstring php-xml php-json php-opcache unzip git
```

### 2) Install Composer
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

### 3) Oracle Instant Client + OCI8
```bash
sudo dnf install -y libaio
# install oracle-instantclient-basic and sdk rpm packages
sudo pecl install oci8
echo "extension=oci8.so" | sudo tee /etc/php.d/20-oci8.ini
```

### 4) Application deploy
```bash
cd /var/www/audit-system
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

### 5) File permissions
```bash
sudo chown -R apache:apache /var/www/audit-system
sudo chmod -R 775 storage bootstrap/cache
```

### 6) SELinux enforcing mode
```bash
sudo chcon -R -t httpd_sys_rw_content_t /var/www/audit-system/storage
sudo chcon -R -t httpd_sys_rw_content_t /var/www/audit-system/bootstrap/cache
sudo setsebool -P httpd_can_network_connect 1
```

### 7) firewalld
```bash
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

## Apache VirtualHost Example
```apache
<VirtualHost *:80>
    ServerName audit.example.com
    DocumentRoot /var/www/audit-system/public

    <Directory /var/www/audit-system/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/httpd/audit_error.log
    CustomLog /var/log/httpd/audit_access.log combined
</VirtualHost>
```

## Nginx Server Block Example
```nginx
server {
    listen 80;
    server_name audit.example.com;
    root /var/www/audit-system/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Production Hardening
- Use HTTPS, HSTS, and secure session cookies.
- Restrict Oracle user account to read-only schema grants.
- Enable centralized log shipping from `storage/logs/laravel.log`.
- Add queue workers via systemd for async notifications and reporting jobs.
