FROM php:8.2-cli-bookworm

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY . /app

RUN chmod +x /app/deploy.sh /app/docker/entrypoint.sh /app/artisan

EXPOSE 8000

ENTRYPOINT ["/app/docker/entrypoint.sh"]
