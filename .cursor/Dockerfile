# Multi-stage Dockerfile for Laravel Development Server
# Stage 1: Node.js for building frontend assets
FROM node:18-alpine AS node-builder

WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production

COPY . .
RUN npm run build

# Stage 2: PHP Development Environment
FROM php:8.2-apache AS development

# Set environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV TZ=America/Sao_Paulo

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nano \
    supervisor \
    cron \
    nodejs \
    npm \
    && docker-php-ext-configure gd \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create application directory
WORKDIR /var/www/html

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy package.json for Node.js dependencies
COPY package*.json ./
RUN npm ci

# Copy application files
COPY . .

# Copy built assets from node-builder stage
COPY --from=node-builder /app/public/build ./public/build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create Laravel specific directories if they don't exist
RUN mkdir -p storage/logs \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/app/public

# PHP Configuration for development
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-dev.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-dev.ini

# Configure OPcache for development
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini \
    && echo "opcache.revalidate_freq=1" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini

# Create a startup script
RUN echo '#!/bin/bash\n\
# Wait for database connection\n\
echo "Waiting for database connection..."\n\
until php artisan migrate:status > /dev/null 2>&1; do\n\
    echo "Database not ready, waiting..."\n\
    sleep 2\n\
done\n\
\n\
# Run Laravel setup commands\n\
echo "Setting up Laravel application..."\n\
php artisan key:generate --force\n\
php artisan storage:link\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Start supervisor to manage multiple processes\n\
echo "Starting services..."\n\
exec "$@"' > /usr/local/bin/laravel-start.sh \
    && chmod +x /usr/local/bin/laravel-start.sh

# Create supervisor configuration
RUN echo '[supervisord]\n\
nodaemon=true\n\
user=root\n\
logfile=/var/log/supervisor/supervisord.log\n\
pidfile=/var/run/supervisord.pid\n\
\n\
[program:apache2]\n\
command=/usr/sbin/apache2ctl -D FOREGROUND\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0\n\
\n\
[program:laravel-queue]\n\
process_name=%(program_name)s_%(process_num)02d\n\
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600\n\
autostart=true\n\
autorestart=true\n\
user=www-data\n\
numprocs=1\n\
redirect_stderr=true\n\
stdout_logfile=/var/www/html/storage/logs/worker.log\n\
\n\
[program:laravel-schedule]\n\
command=bash -c "while [ true ]; do (php /var/www/html/artisan schedule:run --verbose --no-interaction &); sleep 60; done"\n\
autostart=true\n\
autorestart=true\n\
user=www-data\n\
redirect_stderr=true\n\
stdout_logfile=/var/www/html/storage/logs/schedule.log' > /etc/supervisor/conf.d/laravel.conf

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Expose port
EXPOSE 80

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/laravel-start.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

# Development stage with additional tools
FROM development AS development-tools

# Install additional development tools
RUN apt-get update && apt-get install -y \
    vim \
    htop \
    mysql-client \
    redis-tools \
    && composer global require laravel/installer \
    && composer global require phpunit/phpunit

# Install XDebug for development
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# XDebug configuration for development
RUN echo "xdebug.mode=develop,debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Enable composer global binaries
ENV PATH="/root/.composer/vendor/bin:${PATH}"
