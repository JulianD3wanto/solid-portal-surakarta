FROM php:8.3-fpm AS base

# Ekstensi PHP yang dibutuhkan Laravel + SQLite + PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
        libpq-dev sqlite3 \
    && docker-php-ext-install -j$(nproc) \
        pdo_sqlite pdo_pgsql mbstring zip bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# Node.js untuk build aset Vite (Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ---------------------------------------------------------------------------
# Stage build: install dependensi PHP + build aset frontend
# ---------------------------------------------------------------------------
FROM base AS build

COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader --ignore-platform-reqs

COPY . .

RUN npm ci \
    && npm run build \
    && composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi --no-interaction || true

# ---------------------------------------------------------------------------
# Stage produksi
# ---------------------------------------------------------------------------
FROM base

RUN apt-get update && apt-get install -y --no-install-recommends nginx supervisor cron \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

# Pastikan struktur storage & cache ada (disk persistent akan menimpa lewat entrypoint)
RUN mkdir -p storage/framework/{cache/data,sessions,views} storage/logs \
        storage/app/public storage/app/private database bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/app.conf
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/app.conf"]
