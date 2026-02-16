# PHP application container untuk School SaaS.
# Digunakan oleh service: app, scheduler, horizon.
# Extensions: pdo_pgsql, redis, gd, intl, zip, pcntl, bcmath, opcache, exif.
FROM php:8.4-fpm-alpine

ARG UID=1000
ARG GID=1000

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    postgresql-dev \
    linux-headers \
    oniguruma-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        gd \
        intl \
        zip \
        pcntl \
        mbstring \
        exif \
        bcmath \
        opcache

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create application user
RUN addgroup -g ${GID} appuser \
    && adduser -u ${UID} -G appuser -s /bin/sh -D appuser

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY --chown=appuser:appuser . .

# Install PHP dependencies (skip scripts to avoid artisan needing DB/APP_URL at build time)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts \
    && composer dump-autoload --optimize

# Set permissions
RUN chown -R appuser:appuser storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER appuser

EXPOSE 9000

CMD ["php-fpm"]
