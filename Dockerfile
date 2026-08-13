FROM registry.access.redhat.com/ubi8/php-82 as app

WORKDIR /var/www/html

RUN dnf install -y nginx postgresql && dnf clean all

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction || true

COPY . .

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 8080

CMD ["/bin/bash", "-lc", "php-fpm -D && nginx -g 'daemon off;'"]
